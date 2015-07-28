<?php
/**
 +------------------------------------------------------------------------------
 * Web Desktop 系统模块Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-18 下午1:55:54
 +------------------------------------------------------------------------------
 */
class SystemAction extends BaseAction {
    //数据表前缀
    var $db_prefix;
	protected $dsknav;
	public function _initialize() {
		parent::_initialize ();
		$this->db_prefix = C('DB_PREFIX');
		$topnav = M('app_group')->order('display_order ASC,group_id DESC')->select();
		//echo M('app_group')->getLastSql();
		foreach($topnav as $key=>$val){
			$newnav[$val['group_id']] = $val;
		}
		$this->dsknav=$newnav;
	}

	/**
	 * +----------------------------------------------------------
	 * 个人系统设置索引
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-15
	 *         +----------------------------------------------------------
	 */
	public function index() {
		$op = empty ( $_GET ['op'] ) ? $_POST ['op'] : $_GET ['op'];
		// 判断是否是外部提交
		if (! empty ( $op )) {
			$this->$op ();
			exit ();
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 初始化系统应用数据
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间：2013-4-19 上午10:32:48
	 *         +----------------------------------------------------------
	 */
	private function initApps() {
		// 将老的app中的数据导入dsk_app中
		$lastid = M ( 'app' )->order ( 'app_id DESC' )->getField ( 'app_id' );
		// 判断最后一个应用是否存在新应用数据表中(目前还没做优化)
		// if(M('dsk_apps')->where('oid='.$lastid)->find()){
		// 导入开始
		$apps = M ( 'app' )->where ( 'status<>0' )->order ( 'display_order ASC' )->findAll ();
		
		// 获取桌面系统中的app
		foreach ( M ( 'dsk_apps' )->field ( 'appid,oid' )->findAll () as $app ) {
			$apps_0 [$app ['oid']] = $app;
		}
		
		// 过滤应用
		foreach ( $apps as $app ) {
			$apparr = array (
					"oid" => $app ['app_id'],
					"appname" => $app ['app_alias'],
					"appico" => $app ['icon_url'],
					"appdesc" => $app ['description'],
					"appurl" => U ( $app ['app_name'] . '/' . $app ['app_entry'] ),
					"width" => 1024,
					"height" => 600,
					"default" => $app ['status'] == 1 ? 1 : 0,
					"disp" => $app ['status'] == 2 ? 0 : 1,
					"username" => $app ['author_name'],
					"dateline" => $app ['ctime'],
					"classid" => streq ( $app ['group_id'] ),
					"classids" => $app ['group_id'],
					"vendor" => $app ['author_name'],
					"titlebuttons" => "max,min,close", 
					"app_type"  => $app['app_type'],
					"typeid"    => $app['group_id'],
			);
			if (! M ( 'dsk_apps' )->where ( 'oid=' . $app ['app_id'] )->find ()) {
				M ( 'dsk_apps' )->add ( $apparr );
			} else {
				unset ( $map );
				$map ['oid'] = $apparr ['oid'];
				unset ( $apparr ['oid'] );
				M ( 'dsk_apps' )->where ( $map )->save ( $apparr );
			}
			unset ( $apps_0 [$app ['app_id']] );
		}
		
		// }
		// 删除已停用的应用
		foreach ( $apps_0 as $app ) {
			if ($app ['oid']) {
				// M('dsk_apps')->where($app)->delete();
			}
		}
		// 判断系统应用的数量及更新默认系统应用
		// if(M('dsk_icos')->field('icoid')->where("uid=-1 AND username='admin' AND idtype='app'")->count() != M('dsk_apps')->where("`default`=1")->count()){
		// 对icos表中的系统应用数据进行重置
		$def_apps = M ( 'dsk_icos' )->where ( "uid=-1 AND username='admin'" )->findAll ();
		$def_icos = array ();
		foreach ( $def_apps as $value ) {
			$def_icos [$value ['oid']] = $value;
		}
		
		$def_apps = M ( 'dsk_apps' )->where ( "`default`=1" )->findAll ();
		
		foreach ( $def_apps as $key => $app ) {
			// 初始化应用参数
			$icoarr = array (
					'uid' => '-1',
					'username' => 'admin',
					'oid' => $app ['appid'],
					'name' => $app ['appname'],
					'url' => $app ['appurl'],
					'img' => $app ['appico'],
					'wwidth' => empty ( $app ['width'] ) ? 1034 : $app ['width'],
					'wheight' => empty ( $app ['height'] ) ? 600 : $app ['height'],
					'open' => empty ( $app ['open'] ) ? 0 : $app ['open'],
					'haveflash' => empty ( $app ['haveflash'] ) ? 0 : $app ['haveflash'],
					'idtype' => empty ( $app ['idtype'] ) ? 'app' : $app ['idtype'],
					'typeid' => empty ( $app ['typeid'] ) ? 0 : $app ['typeid'],
					'havetask' => empty ( $app ['havetask'] ) ? 1 : $app ['havetask'],
					'isshow' => empty ( $app ['isshow'] ) ? 1 : $app ['isshow'],
					'titlebuttons' => empty ( $app ['titlebuttons'] ) ? 'max,min,close' : $app ['titlebuttons'],
					$app ['titlebuttons'],
					'desktop' => $app['typeid'],
					'notdelete' => $app ['default'],
					'type' => 'app',
					'size' => 0,
					'ext' => '',
					'friend' => 0,
					'dateline' => time () ,
					'app_type' => $app['app_type']
			);
			if (empty ( $def_icos [$app ['appid']] )) { // 如果不存在默认应用的处理
			                                     // icos表中不存在默认应用先删除用户已安装的该应用，然后添加系统默认应用
				M ( 'dsk_icos' )->where ( "idtype='app' AND oid=" . $app ['appid'] )->delete ();
				M ( 'dsk_icos' )->add ( $icoarr );
			} else { // 已存在默认应用的处理
			       // 删除用户个人安装的应用信息
				M ( 'dsk_icos' )->where ( "uid<>-1 AND username<>admin AND idtype='app' AND oid=" . $app ['appid'] )->delete ();
				// 查看系统应用是否有改变
				if (! M ( 'dsk_icos' )->where ( $icoarr )->find ()) {
					$map ['oid'] = $icoarr ['oid'];
					$map ['uid'] = '-1';
					$map ['username'] = 'admin';
					unset ( $icoarr ['oid'] );
					unset ( $icoarr ['uid'] );
					unset ( $icoarr ['username'] );
					M ( 'dsk_icos' )->where ( $map )->save ( $icoarr );
				}
				unset ( $def_icos [$app ['appid']] );
			}
		}
		
		// }
		// 当原始的系统应用被改为非系统应用时
		if (! empty ( $def_icos )) {
			$userlist = M ( 'dsk_icos' )->field ( 'uid,username' )->where ( "uid<>-1 AND username<>'admin' AND idtype='app'" )->group ( 'uid' )->findAll ();
			foreach ( $def_icos as $icos ) {
				// 获取已使用系统的用户列表
				foreach ( $userlist as $uicos ) {
					// 将原始的系统应用被改为非系统应用时将该应用添加给每个人
					$icos ['uid'] = $uicos ['uid'];
					$icos ['username'] = $uicos ['username'];
					M ( 'dsk_icos' )->add ( $icos );
				}
			}
		}
		
		// 同步用户安装的应用
		/* 获取用户在老系统中安装的应用列表 */
		$install_icos = M ()->table ( $this->prefix . 'dsk_apps A' )->join ( $this->prefix . 'user_app U on A.oid = U.app_id' )->field ( 'A.*' )->where ( "A.uid <>- 1
					AND A.username <> 'admin'
					AND A.disp <> 1
					AND U.uid = 2
					AND A.`default` <> 1" )->findAll ();
		$icos_0 = array ();
		foreach ( $install_icos as $value ) {
			$icos_0 [$value ['appid']] = $value;
		}
		/* 获取用户在新系统中的应用 */
		$dsk_icos = M ( 'dsk_icos' )->where ( "uid='$this->mid'" )->findAll ();
		
		$icos_u = array ();
		foreach ( $dsk_icos as $value ) {
			$icos_u [$value ['oid']] = $value;
		}
		
		if (empty ( $icos_0 ))
			unset ( $icos_u );
			
			/* 以老系统中的app信息为准做对比 */
		foreach ( $icos_0 as $value ) {
			if (empty ( $icos_u [$value ['appid']] )) { // dsk表中不存在应用数据时进行添加
				$icoarr = array (
						'uid' => $this->mid,
						'username' => $this->userName,
						'oid' => $value ['appid'],
						'name' => $value ['appname'],
						'url' => $value ['appurl'],
						'img' => $value ['appico'],
						'wwidth' => empty ( $value ['width'] ) ? 1034 : $value ['width'],
						'wheight' => empty ( $value ['height'] ) ? 600 : $value ['height'],
						'open' => empty ( $value ['open'] ) ? 0 : $value ['open'],
						'haveflash' => empty ( $value ['haveflash'] ) ? 0 : $value ['haveflash'],
						'idtype' => empty ( $value ['idtype'] ) ? 'app' : $value ['idtype'],
						'typeid' => empty ( $value ['typeid'] ) ? 0 : $value ['typeid'],
						'havetask' => empty ( $value ['havetask'] ) ? 1 : $value ['havetask'],
						'isshow' => empty ( $value ['isshow'] ) ? 1 : $value ['isshow'],
						'titlebuttons' => empty ( $value ['titlebuttons'] ) ? 'max,min,close' : $value ['titlebuttons'],
						$value ['titlebuttons'],
						'desktop' => $value['desktop'],
						'notdelete' => $value ['default'],
						'type' => 'app',
						'size' => 0,
						'ext' => '',
						'friend' => 0,
						'dateline' => time (),
						'app_type' => $value['app_type']
				);
				M ( 'dsk_icos' )->add ( $icoarr );
			} else {
				unset ( $icos_u [$value ['appid']] );
			}
		}
		/* 删除老系统中已卸载的应用 */
		foreach ( $icos_u as $value ) {
			$map ['oid'] = $value ['oid'];
			$map ['uid'] = $value ['uid'];
			$map ['username'] = $value ['username'];
			$map ['idtype'] = 'app';
			M ( 'dsk_icos' )->where ( $map )->delete ();
		}
		
		/* 同步文件夹应用图标等 */
		$userconfig = M ( 'dsk_userconfig' )->where ( "uid='$this->mid'" )->find ();
		// 个人桌面的图标列表
		$screenlist = dstripslashes ( unserialize ( stripslashes ( $userconfig ['screenlist'] ) ) );

		$icos_0 = $screenlist_array = array ();
		foreach ( $screenlist as $value ) {
			$screenlist_array = $icos_0 = array_merge ( $value ['icos'], $icos_0 );
		}

		// 个人文件夹中的图标列表
		$folderlist = M ( 'dsk_folder' )->field ( 'ids' )->where ( "uid=" . $this->mid )->findAll ();
		$icos_f = array ();
		foreach ( $folderlist as $value ) {
			$icos_f = array_merge ( explode ( ',', $value ['ids'] ), $icos_f );
		}
		// 获取个人桌面图标列表
		$myicos = M ( 'dsk_icos' )->field ( "icoid,type,oid,desktop" )->where ( "uid=-1 or uid=" . $this->mid )->findAll ();
		$firstgroup = 1;
		foreach($this->dsknav as $key=>$val){
			$groupid[$val['group_id']] = $val['group_id'];
			if($key == 0){
				$firstgroup = $val['group_id'];
			}
		}
		$update = 0;
		foreach ( $myicos as $icos ) {
				// 如果现在桌面及文件夹中不存在该图标则添加
				if (! in_array ( $icos ['icoid'], $icos_0 ) && ! in_array ( $icos ['icoid'], $icos_f )) {

					if(in_array($icos['desktop'],$groupid)){
						$screenlist [$icos['desktop']] ['icos'] [] = $icos ['icoid'];
					}else{
						$screenlist [$firstgroup] ['icos'] [] = $icos ['icoid'];
					}
					$update = 1;
				}
		}

		// 如果用户桌面的icos与icos表中的数据不一至则
		$_myicos = array ();
		foreach ( $myicos as $key => $value ) {
			if ($icos ['type'] == 'folder') {
				if (! M ( 'dsk_folder' )->where ( "fid=" . $value ['oid'] . " and desktop = 'sys_2'" )->find ())
					$_myicos [$value ['icoid']] = $value;
			} else {
				$_myicos [$value ['icoid']] = $value;
			}
		}
		
		if (count ( $screenlist_array ) != count ( $_myicos )) {
			foreach ( $screenlist as $skey=>$desktop ) {
				foreach ( $desktop ['icos'] as $key => $value ) {
					if (! $_myicos [$value]) {
						unset ( $desktop ['icos'] [$key] );
					} else {
						unset ( $_myicos [$value] );
					}
				}
				if(!empty($desktop['group_id'])){
					$screenlist [$desktop['group_id']] ['icos'] = array_merge ( $screenlist [$desktop['group_id']] ['icos'], $_myicos );
				}

			}
			$update = 1;
		}
		//print_r($screenlist);
		//dump($screenlist);
		if ($update) { // 重新更新桌面图标
			$userconfig ['screenlist'] = serialize ( $screenlist );
			//M ( 'dsk_userconfig' )->where ( "uid='$this->mid'" )->save ( $userconfig );
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 获取系统json数据
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-3-19 下午2:02:47
	 *         +----------------------------------------------------------
	 */
	public function json() {
		$do = strtolower ( $_GET ['do'] );
		switch ($do) {
			case 'save' : // 保存设置方法
				$this->save ();
				break;
			case 'deleteico' : // 删除应用方法
				$this->deleteIco ();
				break;
			case 'newfolder' : // 创建文件夹
				$this->newfolder ();
				break;
			case 'rename' : // 重新命名
				$this->rename ();
				break;
			default :
				$this->jsonInit ();
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 系统JSON字符串初始化
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-3-21 上午10:52:39
	 *         +----------------------------------------------------------
	 */
	public function jsonInit() {
		global $ts;
		$data = array ();
		// 有新应用时初始化桌面系统已安装应用表
		$this->initApps ();
		
		// 获取系统配置信息
		if ($this->setting ['dsk_sysconfig']) {
			$sysconfig = dstripslashes ( unserialize ( stripslashes ( $this->setting ['dsk_sysconfig'] ) ) );
		} else {
			$sysconfig = get_config ();
		}
		if (intval ( $sysconfig ['userscreennum'] ) < 1) {
			$sysconfig ['userscreennum'] = 1;
		}
		/**
		 * rickerYu ADD 添加动态的导航菜单 2013-12-25
		 */
		$sysconfig['userscreennum'] =count($this->dsknav);

		// 设置ucenter地址
		$sysconfig ['ucenterurl'] = UC_API;
		
		unset ( $sysconfig ['siteuniqueid'] );
		
		// $data['sysconfig'] 此处为desktop的配置信息（样式显示，桌面个数）
		$data ['sysconfig'] = $sysconfig;
		//获取系统配置信息结束
		
		
		// 获取图标视图配置（大图标，中图标，小图标...）
		if ($this->setting ['dsk_iconview']) {
			$iconview = dstripslashes ( unserialize ( stripslashes ( $this->setting ['dsk_iconview'] ) ) );
		} else {
			$result = M ( 'dsk_iconview' )->where ( '`avaliable`>0' )->order ( 'disp' )->findAll ();
			$iconview = array ();
			foreach ( $result as $value ) {
				$iconview [$value ['id']] = $value;
			}
		}
		$data ['iconview'] = $iconview;
		// 获取图标视图配置（大图标，中图标，小图标...） - end
		
		
		// 就算是要获取插件信息－ 不过这个表现在是空 这个变量也没有给$data
		$plugins = array ();
		$pluginappids = array ();
		$result = M ( 'dsk_plugin' )->where ( '`avaliable`>0' )->findAll ();
		foreach ( $result as $value ) {
			if ($value ['datatype'])
				$value ['datatype'] = explode ( ',', $value ['datatype'] );
			else
				$value ['datatype'] = array ();
			$plugins [$value ['pluginid']] = dstripslashes ( $value );
		}
		// 就算是要获取插件信息－ 不过这个表现在是空 这个变量也没有给$data - -end
		
		
		// 获取用户的配置信息，以及桌面图标文件等
		$navids_0 = array ();
		$docklist_0 = $screenlist_0 = array ();
		
		// 获取系统预定义的用户配置模板,先从dsk_userconfig这个表里面查找，如果没有就生成
		if ($config_0 = M ( 'dsk_userconfig' )->where ( "uid='0'" )->find ()) {
			//如果dsk_userconfig表里面存在
			$screenlist_0 = unserialize ( stripslashes ( $config_0 ['screenlist'] ) );
			
			$docklist_0 = explode ( ',', $config_0 ['docklist'] );
			$data ['iconpositions_0'] = unserialize ( stripslashes ( $config_0 ['iconpositions'] ) );
		} else {
			// 好像是获取nav条上的数
			// 如果获取不到系统预定义的系统模板的操作
			if ($this->setting ['dsk_nav']) {
				$navbar = dstripslashes ( unserialize ( stripslashes ( $this->setting ['dsk_nav'] ) ) );
			} else {
				$result = M ( 'dsk_navbar' )->where ( '`avaliable`>0' )->order ( 'disp' )->findAll ();
				$iconview = array ();
				foreach ( $result as $value ) {
					// 重新格式化url 孙晓波于2013年6月7日添加
					if (strrpos ( $value ['navurl'], 'http://' ) !== 0 && strrpos ( $value ['navurl'], 'https://' ) !== 0) {
						$value ['navurl'] = getSiteUrl () . __ROOT__ . '/' . $value ['navurl'];
					}
					$navbar [$value ['navid']] = $value;
				}
			}
			foreach ( $navbar as $value ) {
				if ($value ['navicon'])
					$value ['navicon'] = $this->setting ['attachurl'] . $value ['navicon'];
				$value ['navid'] = 'sys_' . $value ['navid'];
				$screenlist_0 [$value ['navid']] ['config'] = $value;
				$screenlist_0 [$value ['navid']] ['icos'] = explode ( ',', $value ['icos'] );
				$screenlist_0 [$value ['navid']] ['wins'] = array ();
				$screenlist_0 [$value ['navid']] ['widgets'] = array ();
			}
			$setarr = array (
					'uid' => 0,
					'username' => '',
					'docklist' => '',
					'screenlist' => serialize ( $screenlist_0 ),
					'iconpositions' => serialize ( array () ),
					'dateline' => time () ,
					'current'   => $sysconfig['defaultdesktop']
			);
			M ( 'dsk_userconfig' )->add ( $setarr );
		}
		
		
		// 获取公共的桌面扩展nav
		if ($this->setting ['dsk_nav']) {
			$navbar = dstripslashes ( unserialize ( stripslashes ( $this->setting ['dsk_nav'] ) ) );
		} else {
			$result = M ( 'dsk_navbar' )->where ( '`avaliable`>0' )->order ( 'disp' )->findAll ();
			$navbar = array ();
			foreach ( $result as $value ) {
				// 重新格式化url 孙晓波于2013年6月7日添加
				if (strrpos ( $value ['navurl'], 'http://' ) !== 0 && strrpos ( $value ['navurl'], 'https://' ) !== 0) {
					$value ['navurl'] = getSiteUrl () . __ROOT__ . '/' . $value ['navurl'];
				}
				$navbar [$value ['navid']] = $value;
			}
		}

		$needsave_screenlist_0 = 0;
		if (count ( $screenlist_0 ) < count ( $navbar )) {
			$needsave_screenlist_0 = 1;
		}
		
		foreach ( $navbar as $value ) {
			if ($value ['navicon'])
				$value ['navicon'] = $this->setting ['attachurl'] . $value ['navicon'];
			$value ['navid'] = 'sys_' . $value ['navid'];
			$screenlist_0 [$value ['navid']] ['config'] = $value;
			if (! $screenlist_0 [$value ['navid']] ['icos'])
				$screenlist_0 [$value ['navid']] ['icos'] = array ();
			if (! $screenlist_0 [$value ['navid']] ['wins'])
				$screenlist_0 [$value ['navid']] ['wins'] = array ();
			if (! $screenlist_0 [$value ['navid']] ['widgets'])
				$screenlist_0 [$value ['navid']] ['widgets'] = array ();
			$navids_0 [] = $value ['navid'];
		}
		
		if ($needsave_screenlist_0) {
			M ( 'dsk_userconfig' )->where ( "uid='0'" )->save ( array (
					'screenlist' => addslashes ( serialize ( $screenlist_0 ) ) 
			) );
		}
		
		$allicoids_0 = array ();
		$gids = array ();
		foreach ( $screenlist_0 as $key => $desktop ) {
			if (! in_array ( $key, $navids_0 )) {
				unset ( $screenlist_0 [$key] );
				continue;
			}
			if ($desktop ['config'] ['isdefault']) {
				$data ['currentDesktop'] = $screenlist_0 ['current'] ? $screenlist_0 ['current'] : $key;
			}
			$tempicos = array ();
			foreach ( $desktop ['icos'] as $icoid => $value ) {
				$allicoids_0 [] = $value;
				$tempicos [] = $value;
			}
			$screenlist_0 [$key] ['icos'] = $tempicos;
			foreach ( $desktop ['wins'] as $icoid => $value ) {
				$allicoids_0 [] = $value ['icoid'];
			}
			foreach ( $desktop ['widgets'] as $icoid => $value ) {
				$gids [] = $value ['gid'];
			}
		}
		// 给导航的桌面上增加图标 2013/6/18 孙晓波添加 start
		foreach ( $screenlist_0 as $key => $value ) {
			if (! empty ( $value ['config'] ['icos'] )) {
				$screenlist_0 [$key] ['icos'] = explode ( ',', $value ['config'] ['icos'] );
			}
		}
		// 给导航的桌面上增加图标 2013/6/18 孙晓波添加 end
		
		
		/*
		 * 这里面的 $allicoids_0 = array(),$docklist_0 = array(0=>array())
		 */
		if (! $this->mid)
			$allicoids_0 = array_merge ( $allicoids_0, $docklist_0 );
		$navids_u = array ();
		$docklist_u = $screenlist_u = array ();
		
		/**
		 * $this->mid = lhyfe@sohu.com = 120
		 * 下面是这个是当用户登录之后 进行的操作
		 */
		if ($this->mid) {
			// 如果是当前登录的用户
			/*
			 * $config_u = 是从dsk_userconfig表里面找uid=120的信息， 这里面我把表里面的记录删除了，也就是 $config_u = null $config_u = 这个是dsk_userconfig里面的记录， 里面是用字符串的形式记录着用户的基本配置信息
			 */
			
			$config_u = M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->find ();
			
			if ($config_u) {
				/*
				 * $config_u就是用户的基本配置信息，现在找到了，下面把他还原成数组的形式 //获取用户的个人桌面图标（每一页的图标）
				 */
				$screenlist_u = unserialize ( stripslashes ( $config_u ['screenlist'] ) );
				
				/**
				 * 获取个人广场的桌面文件夹
				 * 
				 * @var unknown_type
				 */
				$navbarlist_u = unserialize ( stripslashes ( $config_u ['navbarlist'] ) );
				
				/*
				 * $screenlist_u就是用户屏幕的基本配置的数组信息，主要是屏幕的基本信息 注意：这里面的icos现在初始化的时候，是空的，没有内容，但是桌面上还是显示7个应用了 具体怎么处理的，下面看一下
				 */
				foreach ( $screenlist_u as $key => $value ) {
					$navids_u [] = "$key";
				}
				
				/*
				 * $navids_u = Array( [0] => 1,[1] => 2,[2] => 3,[3] => 4,[4] => 5) 这种形式的，主要是用于显示5个屏幕的ids
				 */
    			/*
    			 * 首次初始化用户时获取系统默认应用(新添加)
    			* $needsave_screenlist默认是0，如果是1的话，就是初次使用，进行相应的操作
    			*/
    			$needsave_screenlist = 0;
				/*
				 * 得到用户要显示的屏幕数量，循环显示出屏幕 初始化一定数量的屏幕 判断原来的屏幕与新屏幕个数 这个地方里面的$screenlist_u['icos']是个空数组 注意这里面的，有个判断$needsave_screenlist及in_array 上面已经得到用户自定义配置里面的$navids_u，这个变量了 在下面做一下判断 ，如果系统那边的桌面数量修改了，就初始化系统添加的新桌面 同时 $needsave_screenlist = 1，为下面的更新用户配置到数据库做准备
				 */
				/*
				for($i = 1; $i <= $sysconfig ['userscreennum']; $i ++) {
					if (! in_array ( "$i", $navids_u )) {
						
						$navids_u [] = "$i";
						$screenlist_u [$i] ['config'] = array (
								'navid' => $i,
								//'navname' => L ( 'desktop' ) . $i,
								'navname' => $this->dsknav[$i]['title'],
								'type' => 'desktop',
								'autolist' => $sysconfig ['autolist'],
								'marginleft' => $sysconfig ['marginleft'],
								'margintop' => $sysconfig ['margintop'],
								'marginright' => $sysconfig ['marginright'],
								'marginbottom' => $sysconfig ['marginbottom'],
								'iconview' => $sysconfig ['iconview'],
								'iconposition' => $sysconfig ['iconposition'],
								'dockshow' => $sysconfig ['dockshow'],
								'topbarshow' => $sysconfig ['topbarshow'] 
						);
						$screenlist_u [$i] ['icos'] = array ();
						$screenlist_u [$i] ['wins'] = array ();
						$screenlist_u [$i] ['widgets'] = array ();
						$needsave_screenlist = 1;
					}
				}
    			*/
				$navids_u = array();
				$firstnavid = '';
				foreach($this->dsknav as $key=>$value){
					if(empty($firstnavid)){
						$firstnavid = $value['group_id'];
					}
					if (! in_array ( $value['group_id'], $navids_u )) {
						$navids_u [] = $value['group_id'];
						$screenlist_u [$value['group_id']] ['config'] = array (
							'navid' => $value['group_id'],
							//'navname' => L ( 'desktop' ) . $i,
							'navname' => $value['group_name'],
							'type' => 'desktop',
							'autolist' => $sysconfig ['autolist'],
							'marginleft' => $sysconfig ['marginleft'],
							'margintop' => $sysconfig ['margintop'],
							'marginright' => $sysconfig ['marginright'],
							'marginbottom' => $sysconfig ['marginbottom'],
							'iconview' => $sysconfig ['iconview'],
							'iconposition' => $sysconfig ['iconposition'],
							'dockshow' => $sysconfig ['dockshow'],
							'topbarshow' => $sysconfig ['topbarshow']
						);
						$screenlist_u [$value['group_id']] ['icos'] = array ();
						$screenlist_u [$value['group_id']] ['wins'] = array ();
						$screenlist_u [$value['group_id']] ['widgets'] = array ();
						$needsave_screenlist = 1;
					}
				}
    			/*
				 * 这个里面的$sysconfig就是应用的配置信息，应该是从表ts_dsk_config表里面调用出来的 下面这个函数的操作就是为了删除比配置中的屏幕数量多的屏幕
				 */
				foreach ( $screenlist_u as $key => $value ) {
    				/*
    				 * desktop_move_userdata
    				* 函数的作用：是为了，把多于规定桌面数量的桌面里面的图标，插件，wins合并
    				*/
					//if ($key > $sysconfig ['userscreennum']) {
					if (!in_array ( $key, $navids_u )) {
						$screenlist_u [$firstnavid] = desktop_move_userdata ( $key, $this->mid, $firstnavid );
						unset ( $screenlist_u [$key] );
						foreach ( $navids_u as $navid => $value ) {
							if ($navid == $key)
								unset ( $navids_u [$key] );
						}
					}else{
						/*
						 * 上面是判断屏幕列表中的key = group_id在不在$navids_u = 这个是从app_group表中出来的
						 * 如果不在，就unset了，如果在，就添加下面的属性
						 * 同时注释下面的方法放到这个else里面
						 * rickeryu add 2013-12-26
						 */
						$screenlist_u[$key]['config']['marginleft']=$sysconfig['marginleft'];
						$screenlist_u[$key]['config']['margintop']=$sysconfig['margintop'];
						$screenlist_u[$key]['config']['marginright']=$sysconfig['marginright'];
						$screenlist_u[$key]['config']['marginbottom']=$sysconfig['marginbottom'];
					}
    			}
    			/*
    			 * 这个应该是界面的配置信息吧，具体这里不说了，就是赋值的操作
    			*/
				/*
    			foreach($screenlist_u as $key=>$value){
    				$screenlist_u[$key]['config']['marginleft']=$sysconfig['marginleft'];
    				$screenlist_u[$key]['config']['margintop']=$sysconfig['margintop'];
    				$screenlist_u[$key]['config']['marginright']=$sysconfig['marginright'];
    				$screenlist_u[$key]['config']['marginbottom']=$sysconfig['marginbottom'];
    			}
    			*/
    			/*
    			 * 这个应该是下面的dock的显示操作的
    			*/
    			if(!empty($config_u['docklist']))
    				$docklist_u=explode(',',$config_u['docklist']);
    			$data['iconpositions_u']=unserialize(stripslashes($config_u['iconpositions']));
    			/*
    			 * $needsave_screenlist = 1的时候，有一种情况
    			* 后台规定的屏幕数量发生的变化时，这里面要把新的系统配置更新到用户配置里面
    			*/
    			
    			if($needsave_screenlist){
    				M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('screenlist'=>addslashes(serialize($screenlist_u))));
    			}
    		}else{
    			/*
    			 * @author Rickeryu
    			 * @description 这个else里面的主要功能，就是当用户登录的时候，但是没有来过desktop应用的时候，
    			 * 在ts_dsk_userconfig这个表里面没有基本的信息，就是要把初始化的数组信息生成字符串的形式存入到ts_dsk_userconfig表
    			 */
    			/*
    			 * 初始化屏幕操作
    			 * $navids_u 用户自己定义的屏幕数量id
    			 * $screenlist_u 用户屏幕初始化的基本配置信息
    			 * 重点是里面的icos，这个是桌面图标的记录数组
    			 */
				/*
    			for($i = 1; $i <= $sysconfig ['userscreennum']; $i ++) {
					$navids_u [] = "$i";
					$screenlist_u [$i] ['config'] = array (
							'navid' => $i,
							//'navname' => '桌面' . $i,
							'navname' => $this->dsknav[$i]['title'],
							'type' => 'desktop',
							'autolist' => $sysconfig ['autolist'],
							'marginleft' => $sysconfig ['marginleft'],
							'margintop' => $sysconfig ['margintop'],
							'marginright' => $sysconfig ['marginright'],
							'marginbottom' => $sysconfig ['marginbottom'],
							'iconview' => $sysconfig ['iconview'],
							'iconposition' => $sysconfig ['iconposition'],
							'dockshow' => $sysconfig ['dockshow'],
							'topbarshow' => $sysconfig ['topbarshow'] 
					);
					$screenlist_u [$i] ['icos'] = array ();
					$screenlist_u [$i] ['wins'] = array ();
					$screenlist_u [$i] ['widgets'] = array ();
				}
				*/
				foreach($this->dsknav as $key=>$value){

					$navids_u [] = $value['group_id'];
					$screenlist_u [$value['group_id']] ['config'] = array (
						'navid' => $value['group_id'],
						//'navname' => L ( 'desktop' ) . $i,
						'navname' => $value['group_name'],
						'type' => 'desktop',
						'autolist' => $sysconfig ['autolist'],
						'marginleft' => $sysconfig ['marginleft'],
						'margintop' => $sysconfig ['margintop'],
						'marginright' => $sysconfig ['marginright'],
						'marginbottom' => $sysconfig ['marginbottom'],
						'iconview' => $sysconfig ['iconview'],
						'iconposition' => $sysconfig ['iconposition'],
						'dockshow' => $sysconfig ['dockshow'],
						'topbarshow' => $sysconfig ['topbarshow']
					);
					$screenlist_u [$value['group_id']] ['icos'] = array ();
					$screenlist_u [$value['group_id']] ['wins'] = array ();
					$screenlist_u [$value['group_id']] ['widgets'] = array ();
				}
				/*
				 * 桌面初始化结束 生成的$screenlist_u就是一个具有你设置的桌面数的数组 里面的每一个的icos是空的
				 */
    			
    			/*
    			 * docklist 这里应该是桌面下面的dock工具栏数组
    			 */
    			$docklist_u = array ();
				// 这里原来是个乱码，具体干什么的，现在偶也不知道
				$setupwids = array ();
				/*
				 * 应该是从插件表里面调出要显示的插件， 这里面现在是个空的
    			 */
    			$result = M('dsk_widget')->where("uid='-1'")->findAll();
    			/*
				 * $result = 空，但不是NUll啊，输出是个空 下面的循环现在没有走
				 */
				foreach ( $result as $value ) {
					
					if (! $value ['notdelete']) {
						unset ( $value ['gid'] );
						$value ['uid'] = $this->mid;
						$value ['username'] = getRealName ( $this->mid );
						$value ['gid'] = M ( 'dsk_widget' )->add ( $value );
					}
					
					if ($value ['desktop'] > 0 && in_array ( $value ['desktop'], $navids_u )) {
						$gidarr = array (
								'gid' => $value ['gid'],
								'navid' => $value ['desktop'],
								'left' => 0,
								'top' => 0,
								'zIndex' => 100 
						);
						$screenlist_u [$value ['desktop']] ['widgets'] [$value ['gid']] = $gidarr;
					}
					$setupwids [] = $value ['oid'];
				}
				/*
				 * $result是空的 这个循环也没有走
				 */
				// 获取系统应用信息
				$setupids = array ();
				foreach ( $result as $value ) {
					// 如果该系统应用是可以删除的情况下
					if (! $value ['notdelete']) {
						unset ( $value ['icoid'] );
						$value ['uid'] = $this->mid;
						$value ['username'] = getRealName ( $this->mid );
						$value ['icoid'] = M ( 'dsk_icos' )->add ( $value );
					}
					if ($value ['desktop'] < 0)
						$docklist_u [] = $value ['icoid'];
					if ($value ['desktop'] > 0)
						$screenlist_u [$value ['desktop']] ['icos'] [] = $value ['icoid'];
					$setupids [] = $value ['oid'];
				}
				$square_folder = M ()->table ( C ( 'DB_PREFIX' ) . 'dsk_folder as folder' )->join ( C ( 'DB_PREFIX' ) . 'dsk_icos as icos on icos.oid=folder.fid' )->field ( 'icos.icoid' )->where ( "folder.uid=" . $this->mid . " and folder.desktop='sys_2'" )->findall ();
				foreach ( $square_folder as $value ) {
					$navbar_list ['sys_2'] ['icos'] [] = $value ['icoid'];
				}
				
				$setarr = array (
						'uid' => $this->mid,
						'username' => getRealName ( $this->mid ),
						'docklist' => implode ( ',', $docklist_u ? $docklist_u : $docklist_0 ),
						'navbarlist' => serialize ( $navbar_list ),
						'screenlist' => serialize ( $screenlist_u ),
						'iconpositions' => serialize ( array () ),
						'dateline' => time (),
						'current' => $sysconfig['defaultdesktop']
				);
				/*
				 * 当登录成功的时候，但是ts_dsk_userconfig这个表里面没有uid对应信息的时候，下面就是把上面的初始化信息 插入到ts_dsk_userconfig这个表里面 （这个地方 ，小伟那边也不插入，具体的没有调试）
				 */
				M ( 'dsk_userconfig' )->add ( $setarr );
				/*
				 * 更新各个应用的安装使用情况 更新统计数据
				 */
				if ($setupids)
					M ()->query ( "update " . C ( 'DB_PREFIX' ) . "dsk_apps set setupnum=setupnum+1 where appid IN (" . dimplode ( $setupids ) . ")" );
				if ($setupwids)
					M ()->query ( "update " . C ( 'DB_PREFIX' ) . "dsk_widget_market set setupnum=setupnum+1 where appid IN (" . dimplode ( $setupids ) . ")" );
			}
		}
		$allicoids_u = array ();
		
		/*
		 * $screenlist_u这个是各个不同屏幕的配置信息，这个里面第一个屏幕里面的已经有了用户在dsk_icos里面的应用显示 *
		 */
		foreach ( $screenlist_u as $key => $desktop ) {
			// 这个应该是设置当前是哪个屏幕的
			if ($desktop ['config'] ['isdefault']) {
				$data ['currentDesktop'] = $key;
			}
			// 遍历每个屏幕里面的icos这个数组，现在应该是只有第一个有数据,为了得到$allicoids_u这个变量
			foreach ( $desktop ['icos'] as $icoid => $value ) {
				$allicoids_u [] = $value;
			}
			// ???这里面为什么要把wins里面的也放到icoid里面，这个wins是什么意思啊
			foreach ( $desktop ['wins'] as $icoid => $value ) {
				$allicoids_u [] = $value ['icoid'];
			}
			foreach ( $desktop ['widgets'] as $icoid => $value ) {
				$gids [] = $value ['gid'];
			}
		}
		/*
		 * 上面 的操作应该是遍历了全部的屏幕，用来得到现在已经有的$allicoids_u和$gids
		 */
    	/*
    	 * 这里面为什么要合并这两个数组，不知道
    	 */
    	if (! empty ( $docklist_u )) {
			$allicoids_u = array_merge ( $allicoids_u, $docklist_u );
		}
		
		/*
		 * 如果用户已经登录，下面进行处理
		 */
		if ($this->mid) {
			// 获取系统默认应用(重构)
			/*
			 * 获取系统默认的应用，也就是dsk_icos里面uid = -1 and notdelte = 1的应用
			 */
			$result = M ( 'dsk_icos' )->where ( "uid='-1' and notdelete=1" )->findAll ();
			/*
			 * 下面的循环的作用是： 先判断系统默认的应用在不在上面 得到的全部用户自定义的应用当中 如果没有，在进行是桌面上的应用还是普通应用的 如果desktop大于0，就是在desktop上这个id的桌面上显示，如果是0，就不显示了 如果desktop == -1，这个是判断 什么的，不太懂 $allicoids_u这个icos全部的变量及$docklist_u变量里
			 */
			foreach ( $result as $value ) {
				if (! in_array ( $value ['icoid'], $allicoids_u )) {

					/*
					 * Rickeryu add 2013-12-25
					 * 添加一个desktop判断的处理，desktop = old-group_id
					 */
					$dsk = explode(',',$value ['desktop']);
					foreach($dsk as $dkey=>$dval){
						if ($dval > 0 && in_array ( $dval, $navids_u )) {
							$screenlist_u [$dval] ['icos'] [] = $value ['icoid'];
							$allicoids_u [] = $value ['icoid'];
						} elseif ($dval == - 1) {
							$docklist_u [] = $value ['icoid'];
							$allicoids_u [] = $value ['icoid'];
						}
					}
					/*
					if ($value ['desktop'] > 0 && in_array ( $value ['desktop'], $navids_u )) {
						$screenlist_u [$value ['desktop']] ['icos'] [] = $value ['icoid'];
						$allicoids_u [] = $value ['icoid'];
					} elseif ($value ['desktop'] == - 1) {
						$docklist_u [] = $value ['icoid'];
						$allicoids_u [] = $value ['icoid'];
					}
					*/

				}
			}

			/*
			 * 获取系统挂件 思路应该是和上面 一样的，只不是全部的变量是$gids
			 */
			$result = M ( 'dsk_widget' )->where ( "uid='-1' and notdelete>0" )->findAll ();
			foreach ( $result as $key => $value ) {
				if (! in_array ( $value ['gid'], $gids )) {
					if ($value ['desktop'] > 0 && in_array ( $value ['desktop'], $navids_u )) {
						$gidarr = array (
								'gid' => $value ['gid'],
								'navid' => $value ['desktop'],
								'left' => 0,
								'top' => 0,
								'zIndex' => 100 
						);
						$screenlist_u [$value ['desktop']] ['widgets'] [$value ['gid']] = $gidarr;
						$gids [] = $value ['gid'];
					}
				}
				$result [$key] ['url'] = replace_url ( $value ['url'] );
			}
		}


		// $allicoids=array_merge($allicoids_u,$allicoids_0);
		// var_dump($ts['userAppLimit']);exit;
		$userAppLimit = $ts ['userAppLimit'];
		if ($userAppLimit) {
			$userAppLimits = array ();
			$userSquareLimit = array ();
			foreach ( $userAppLimit as $value ) {
				if ($value ['appid'])
					$userAppLimits [] = $value ['appid'];
				else if ($value ['cid'])
					$userSquareLimits [] = $value ['cid'];
			}
			// var_dump($userSquareLimits);exit;
			foreach ( $allicoids_u as $key => $val ) {
				if (empty ( $val ))
					unset ( $allicoids_u [$key] );
			}
			foreach ( $allicoids_0 as $key => $val ) {
				if (empty ( $val ))
					unset ( $allicoids_0 [$key] );
			}
			
			// 查找已经安装但是未有权限的应用，并删除
			$icoids = array_merge ( $allicoids_u, $allicoids_0 );
			$map ['icoid'] = array (
					'in',
					$icoids 
			);
			
			$allicoids_u = array_flip ( $allicoids_u );
			$allicoids_0 = array_flip ( $allicoids_0 );
			$dskicos = M ( 'dsk_icos' )->field ( 'icoid,type,oid' )->where ( $map )->findall ();
			$unsquarearray = array ();
			$unapparray = array ();
			foreach ( $dskicos as $key => $val ) {
				if ($val ['oid'] && $val ['type'] == 'app') {
					if (! in_array ( $val ['oid'], $userAppLimits )) {
						$unapparray [] = $val ['icoid'];
						unset ( $allicoids_u [$val ['icoid']] );
					}
				} else if ($val ['oid'] && $val ['type'] == 'link') {
					if (! in_array ( $val ['oid'], $userSquareLimits )) {
						$unsquarearray [] = $val ['icoid'];
						unset ( $allicoids_0 [$val ['icoid']] );
					}
				}
			}
			
			$allicoids_u = array_flip ( $allicoids_u );
			$allicoids_0 = array_flip ( $allicoids_0 );
			sort ( $allicoids_0 );
			$screenlist_0 ['sys_2'] ['config'] ['icos'] = implode ( ',', $allicoids_0 );
			$screenlist_0 ['sys_2'] ['icos'] = $allicoids_0;
			// dump($allicoids);exit;
			// 如果存在已经安装，但是在统一身份中没有权限，则进行修改个人大桌面配置表和用户自定义安装表
			if ($unapparray) {
				foreach ( $screenlist_u as $key => $val ) {
					$screenlist_u [$key] ['icos'] = array_diff ( $val ['icos'], $unapparray );
					sort ( $screenlist_u [$key] ['icos'] );
				}
				M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( array (
						'screenlist' => addslashes ( serialize ( $screenlist_u ) ) 
				) );
				$option ['icoid'] = array (
						'in',
						$unapparray 
				);
				$option ['uid'] = $this->mid;
				M ( 'dsk_icos' )->where ( $option )->delete ();
			}
		} else {
			unset ( $allicoids );
		}

		
		if ($navbarlist_u ['sys_2'] ['icos']) {
			$allicoids_0 = array_merge ( $allicoids_0, $navbarlist_u ['sys_2'] ['icos'] );
			$screenlist_0 ['sys_2'] ['icos'] = array_merge ( $screenlist_0 ['sys_2'] ['icos'], $navbarlist_u ['sys_2'] ['icos'] );
		}
		// 得到用户应用列表信息
		$allicoids = array_merge ( $allicoids_u, $allicoids_0 );
		// dump($allicoids);exit;
		/*
		 * RickerYu 添加的，过滤数组值为空的操作
		 */
		
		// print_r($allicoids);
		/*
		 * 下面这个是根据条件产生的ID生成图标所需的必要参数
		 */
		// var_dump($allicoids);exit;
		$icosdata = $icoids = $folderids = $appids = $videoids = $linkids = $imageids = $uids = $blogids = $musicids = $aids = array ();
		if ($allicoids) {
			$map ['icoid'] = array (
					'in',
					$allicoids 
			);
			$result = M ( 'dsk_icos' )->where ( $map )->findAll ();
			foreach ( $result as $value ) {
				if ($value ['type'] == 'folder')
					$folderids [] = $value ['oid'];
				$value ['url'] = replace_param ( $value ['url'] );
				$value ['fsize'] = formatsize ( $value ['size'] );
				$value ['ftype'] = getFileTypeName ( $value ['type'], $value ['ext'] );
				$value ['fdateline'] = dgmdate ( $value ['dateline'] );
				if (! $value ['ext'])
					$value ['ext'] = strtolower ( substr ( strrchr ( $value ['url'], '.' ), 1, 10 ) );
				$icosdata [$value ['icoid']] = $value;
				
				if ($value ['isshow']) {
					$icoids [] = $value ['icoid'];
				}
			}
		}
		// var_dump($icosdata);exit;
		// exit();
		
		$folderdata = array ();
		if ($folderids) {
			$result = M ( 'dsk_folder' )->where ( 'fid IN (' . dimplode ( $folderids ) . ')' )->findAll ();
			$folderids = array ();
			foreach ( $result as $value ) {
				if ($value ['ids'])
					$value ['ids'] = explode ( ',', $value ['ids'] );
				else
					$value ['ids'] = array ();
				$folderdata [$value ['fid']] = $value;
				$folderids [] = $value ['fid'];
			}
		}
		
		/*
		 *
		 */
		foreach ( $screenlist_u as $key => $desktop ) {
			if (! in_array ( $key, $navids_u ))
				unset ( $screenlist_u [$key] );
			foreach ( $desktop ['icos'] as $icoid => $value ) {
				if (! in_array ( $value, $icoids )) {
					unset ( $screenlist_u [$key] ['icos'] [$icoid] );
				}
			}
		}

		foreach ( $screenlist_0 as $key => $desktop ) {
			if (! in_array ( $key, $navids_0 ))
				unset ( $screenlist_0 [$key] );
			foreach ( $desktop ['icos'] as $icoid => $value ) {
				if (! in_array ( $value, $icoids )) {
					unset ( $screenlist_0 [$key] ['icos'] [$icoid] );
				}
			}
		}
		
		if (! $this->mid) {
			$docklist = array ();
			foreach ( $docklist_0 as $key => $value ) {
				if (in_array ( $value, $icoids )) {
					$docklist [] = $value;
				}
			}
		} else {
			$docklist = array ();
			foreach ( $docklist_u as $key => $value ) {
				if (in_array ( $value, $icoids )) {
					$docklist [] = $value;
				}
			}
		}
		
		$widgetdata = array ();
		$gids_all = array ();
		if ($gids) {
			$result = M ( 'dsk_widget' )->where ( "gid IN(" . dimplode ( $gids ) . ")" )->findAll ();
			foreach ( $result as $value ) {
				$gids_all [] = $value ['gid'];
				$value ['url'] = replace_url ( $value ['url'] );
				$widgetdata [$value ['gid']] = $value;
			}
		}
		
		foreach ( $screenlist_u as $key => $desktop ) {
			foreach ( $desktop ['widgets'] as $icoid => $value ) {
				if (! in_array ( $icoid, $gids_all )) {
					unset ( $screenlist_u [$key] ['widgets'] [$icoid] );
				}
			}
		}


		foreach ( $screenlist_0 as $key => $desktop ) {
			foreach ( $desktop ['widgets'] as $icoid => $value ) {
				if (! in_array ( $icoid, $gids_all )) {
					unset ( $screenlist_0 [$key] ['widgets'] [$icoid] );
				}
			}
		}
		$data ['formhash'] = $this->formhash;
		
		$data ['navids'] = array_merge ( $navids_0, $navids_u );
		$data ['docklist'] = $docklist;
		
		$data ['screenlist'] = array (
				'screenlist_0' => $screenlist_0,
				'screenlist_u' => $screenlist_u 
		);
		$data ['sourceids'] = array (
				'icos' => $icoids,
				'folder' => $folderids 
		);
		$data ['sourcedata'] = array (
				'icos' => $icosdata ? $icosdata : array (),
				'folder' => $folderdata ? $folderdata : array (),
				'plugin' => $plugins,
				'widgets' => $widgetdata 
		);
		
		$space = $this->space; // 获取当前用户的个人空间设置重要
		$space ['attachextensions'] = $space ['attachextensions'] ? explode ( ',', $space ['attachextensions'] ) : array ();
		$data ['space'] = $space;
		
		$arr = array ();
		if ($this->mid)
			$arr = M ( 'dsk_thame' )->where ( "id='{$config_u[thame]}'" )->find ();
		if (! $arr) {
			$arr = M ( 'dsk_thame' )->where ( "`default`='1'" )->find ();
		}
		if (! $arr ['backimg'])
			$arr ['backimg'] = __THEME__ . '/desktop/styles/thame/' . $arr ['folder'] . '/back.jpg'; // 桌面背景设置
				                                                                                                     // 重新格式化url
		$arr ['url'] = replace_url ( $arr ['url'] );
		// print_r($arr);
		$data ['thame'] ['system'] = $arr;
		$custom_backimg = '';
		
		$data ['thame'] ['custom'] = array (
				'custom_backimg' => $config_u ['custom_backimg'] ? $config_u ['custom_backimg'] : '',
				'custom_url' => $config_u ['custom_url'] ? $config_u ['custom_url'] : '',
				'custom_window' => $config_u ['custom_window'] ? $config_u ['custom_window'] : '',
				'custom_browser' => $config_u ['custom_browser'] ? $config_u ['custom_browser'] : '',
				'custom_topbar' => $config_u ['custom_topbar'] ? $config_u ['custom_topbar'] : '',
				'custom_filemanage' => $config_u ['custom_filemanage'] ? $config_u ['custom_filemanage'] : '',
				'custom_dock' => $config_u ['custom_dock'] ? $config_u ['custom_dock'] : '',
				'custom_btype' => $config_u ['custom_btype'] ? $config_u ['custom_btype'] : '' 
		);
		
		$data ['thame'] = dstripslashes ( $data ['thame'] );
		if ($config_u ['current'] && (in_array ( $config_u ['current'], $navids_0 ) || in_array ( $config_u ['current'], $navids_u )))
			$data ['currentDesktop'] = $config_u ['current'];
		if (! $data ['currentDesktop'])
			$data ['currentDesktop'] = $sysconfig['defaultdesktop'];
		if ($this->space ['self']) {
			$ukey = random ( 16 );
			if (! M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "ukey" )) {
				M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( array (
						'ukey' => $ukey 
				) );
				$data ['ukey'] = $ukey;
			} else {
				$data ['ukey'] = $config_u ['ukey'];
			}
			/*
			 * if(M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('ukey'=>$ukey))){ $data['ukey']=$ukey; }else{ $data['ukey']=$config_u['ukey']; }
			 */
		}
		
		// dump($data);exit;
		echo json_encode ( $data );
		exit ();
	}
	
	
	/**
	 * +----------------------------------------------------------
	 * 个人主题设置的保存方法（_config.js JS调用�?
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-3-21 上午10:56:39
	 *         +----------------------------------------------------------
	 */
	public function save() {
		if (! $this->space ['self'])
			exit ();
		$arr = array (
				'msg' => 'success' 
		);
		$ukey = trim ( $_GET ['ukey'] );
		$result = M ( 'dsk_userconfig' )->field ( 'ukey' )->where ( "uid='{$this->mid}'" )->find ();
		if ($ukey != $result ['ukey']) {
			$arr ['msg'] = L ( 'need_refresh' );
		} else { // 当用户不是重复提交时
			if ($_REQUEST ['folderlist']) {
				$folders = unserialize ( stripslashes ( trim ( $_REQUEST ['folderlist'] ) ) );
				foreach ( $folders as $key => $value ) {
					unset ( $value ['dataline'] );
					$fid = $value ['fid'];
					unset ( $value ['fid'] );
					unset ( $value ['position'] );
					unset ( $value ['opened'] );
					$value ['ids'] = implode ( ',', $value ['ids'] );
					$value ['pfid'] = intval ( trim ( $value ['pfid'] ) );
					$value ['updatetime'] = time ();
					M ( 'dsk_folder' )->where ( "fid='{$fid}'" )->save ( $value );
				}
			}
			
			$setarr = array ();
			$docklist = $_REQUEST ['docklist'];
			if (isset ( $docklist ))
				$setarr ['docklist'] = trim ( $docklist );
			$screenlist = $_REQUEST ['screenlist'];
			if ($screenlist) {
				if (unserialize ( stripslashes ( trim ( $screenlist ) ) )) {
					$setarr ['screenlist'] = $screenlist;
				}
			}
			$iconpositions_u = $_REQUEST ['iconpositions_u'];
			if (isset ( $iconpositions_u ) && unserialize ( stripslashes ( trim ( $iconpositions_u ) ) )) {
				$setarr ['iconpositions'] = stripslashes ( trim ( $iconpositions_u ) );
			}
			if ($_REQUEST ['spacename'])
				$setarr ['spacename'] = trim ( $_REQUEST ['spacename'] );
			if ($_REQUEST ['friend'] != '')
				$setarr ['friend'] = intval ( $_REQUEST ['friend'] );
			if ($_REQUEST ['current']) {
				$setarr ['current'] = trim ( $_REQUEST ['current'] );
			}
			if ($_REQUEST ['thame']) {
				$setarr ['thame'] = trim ( $_REQUEST ['thame'] );
				$setarr ['custom_backimg'] = trim ( $_REQUEST ['custom_backimg'] );
				$setarr ['custom_url'] = trim ( $_REQUEST ['custom_url'] );
				$setarr ['custom_window'] = trim ( $_REQUEST ['custom_window'] );
				$setarr ['custom_browser'] = trim ( $_REQUEST ['custom_browser'] );
				$setarr ['custom_topbar'] = trim ( $_REQUEST ['custom_topbar'] );
				$setarr ['custom_filemanage'] = trim ( $_REQUEST ['custom_filemanage'] );
				$setarr ['custom_dock'] = trim ( $_REQUEST ['custom_dock'] );
				$setarr ['custom_btype'] = trim ( $_REQUEST ['custom_btype'] );
			}
			$setarr ['updatetime'] = time ();
			M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( $setarr );
			
			// 实时保存，7月2日朱长奇修改
			if ($this->space ['self'] > 1 && $screenlist_0) {
				if (unserialize ( stripslashes ( trim ( $screenlist_0 ) ) )) {
					$navbarlist_0 = array (
							'1',
							'2',
							'3',
							'4',
							'5' 
					);
					$navbarlist ['sys_2'] ['icos'] [] = array_diff ( $screenlist_0 ['sys_2'] ['icos'], $navbarlist_0 );
					if ($navbarlist)
						M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( array (
								'navbarlist' => stripslashes ( trim ( $navbarlist ) ) 
						) );
					$screenlist_0 ['sys_2'] ['icos'] = $navbarlist_0;
					M ( 'dsk_userconfig' )->where ( "uid='0'" )->save ( array (
							'screenlist' => stripslashes ( trim ( $screenlist_0 ) ) 
					) );
				}
			}
			$iconpositions_0 = $_REQUEST ['iconpositions_0'];
			if ($this->space ['self'] > 1 && isset ( $iconpositions_0 ) && unserialize ( stripslashes ( trim ( $iconpositions_0 ) ) )) {
				M ( 'dsk_userconfig' )->where ( "uid='0'" )->save ( array (
						'iconpositions' => stripslashes ( trim ( $iconpositions_0 ) ) 
				) );
			}
		}
		
		echo json_encode ( $arr );
		exit ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 应用评分功能
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-4-12 下午2:36:14
	 *         +----------------------------------------------------------
	 */
	public function score() {
		$id = intval ( $_GET ['id'] );
		$star = intval ( $_GET ['star'] );
		$idtype = trim ( $_GET ['idtype'] );
		if ($this->mid) {
			$scorearr = array (
					'id' => $id,
					'star' => $star,
					'idtype' => $idtype,
					'uid' => $this->mid,
					'username' => getUserName ( $this->mid ),
					'dateline' => time () 
			);
			$arr = array ();
			
			if ($arr = M ( 'dsk_score' )->where ( "uid='{$this->mid}' and idtype='{$idtype}' and  id='{$id}'" )->find ()) {
				M ( 'dsk_score' )->where ( "pid='{$arr[pid]}'" )->save ( $scorearr );
			} else {
				M ( 'dsk_score' )->add ( $scorearr );
			}
			updatescore ( $scorearr ['id'], $idtype );
		}
		
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 删除应用功能
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间：2013-4-23 下午3:36:09
	 *         +----------------------------------------------------------
	 */
	public function deleteIco() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( '`ukey`' )) {
			$arr = array ();
			$arr ['msg'] = 'refresh';
			echo json_encode ( $arr );
			exit ();
		}
		
		$arr = array ();
		$icoid = intval ( $_GET ['icoid'] );
		$desktop = ($_GET ['desktop']);
		$pfid = intval ( $_GET ['pfid'] );
		if ($this->space ['self']) {
			if (($msg = dsk_delete_ico ( $icoid )) == 'success') {
				// 刷新文件夹
				if ($pfid) {
					if ($oids = M ( 'dsk_folder' )->where ( "fid='{$pfid}'" )->getField ( 'ids' )) {
						$ids = explode ( ',', $oids );
						$flag = 0;
						foreach ( $ids as $key => $value ) {
							if ($icoid == $value) {
								$flag = 1;
								unset ( $ids [$key] );
							}
						}
						if ($flag)
							M ( 'dsk_folder' )->where ( "fid='{$pfid}'" )->save ( array (
									'ids' => implode ( ',', $ids ) 
							) );
					}
				} elseif ($desktop) {
					if ($config = M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->find ()) {
						$screenlist = unserialize ( stripslashes ( $config ['screenlist'] ) );
						if ($screenlist [$desktop]) {
							$ids = $screenlist [$desktop] ['icos'];
							foreach ( $ids as $key => $value ) {
								if ($icoid == $value) {
									$flag = 1;
									unset ( $ids [$key] );
								}
							}
							$screenlist [$desktop] ['icos'] = array_values ( $ids );
							M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( array (
									'screenlist' => addslashes ( serialize ( $screenlist ) ) 
							) );
						}
					}
				} elseif ($desktop == - 1) {
					if ($config = M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->find ()) {
						$docklist = explode ( $config ['docklist'] );
						foreach ( $docklist as $key => $value ) {
							if ($icoid == $value) {
								$flag = 1;
								unset ( $docklist [$key] );
							}
						}
						M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->save ( array (
								'docklist' => implode ( ',', $docklist ) 
						) );
					}
				}
				$arr ['msg'] = 'success';
			} else {
				$arr ['msg'] = $msg;
			}
		} else {
			$arr ['msg'] = L ( 'no_privilege' );
		}
		echo json_encode ( $arr );
		exit ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 个人编辑小挂件
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	public function doWidget() {
		if (submitcheck ( 'imagesubmit' )) {
			$setarr = array (
					'url' => trim ( $_POST ['url'] ),
					'width' => intval ( $_POST ['width'] ),
					'height' => intval ( $_POST ['height'] ),
					'classname' => trim ( $_POST ['classname'] ),
					'href' => trim ( $_POST ['href'] ),
					'open' => intval ( $_POST ['open'] ),
					'type' => trim ( $_POST ['type'] ),
					'uid' => intval ( $this->mid ),
					'username' => getUserName ( $this->mid ),
					'dateline' => time () 
			);
		} elseif (submitcheck ( 'linksubmit' )) {
			
			$setarr = array (
					'url' => trim ( $_POST ['url'] ),
					'width' => intval ( $_POST ['width'] ),
					'height' => intval ( $_POST ['height'] ),
					'classname' => trim ( $_POST ['classname'] ),
					'href' => trim ( $_POST ['href'] ),
					'open' => intval ( $_POST ['open'] ),
					'type' => trim ( $_POST ['type'] ),
					'uid' => intval ( $this->mid ),
					'username' => getUserName ( $this->mid ),
					'dateline' => time () 
			);
			// 通过url查找plugin
			if (preg_match ( "/mod=plugin:(\w+)&op=/i", $setarr ['url'], $matches )) {
				$identifier = $matches [1];
				if ($plugin = M ( 'dsk_plugin' )->where ( "identifier='{$identifier}'" )->find ()) {
					$setarr ['idtype'] = 'pluginid';
					$setarr ['typeid'] = $plugin ['pluginid'];
				}
			}
		} elseif (submitcheck ( 'flashsubmit' )) {
			$setarr = array (
					'url' => trim ( $_POST ['url'] ),
					'width' => intval ( $_POST ['width'] ),
					'height' => intval ( $_POST ['height'] ),
					'classname' => trim ( $_POST ['classname'] ),
					'href' => trim ( $_POST ['href'] ),
					'open' => intval ( $_POST ['open'] ),
					'type' => trim ( $_POST ['type'] ),
					'uid' => intval ( $this->mid ),
					'username' => getUserName ( $this->mid ),
					'dateline' => time () 
			);
		}
		$gid = intval ( $_POST ['gid'] );
		if ($gid) {
			if (M ( "dsk_widget" )->where ( "gid='{$gid}'" )->save ( $setarr )) {
				$setarr ['gid'] = $gid;
			}
		} else {
			if ($gid = M ( "dsk_widget" )->add ( $setarr )) {
				$setarr ['gid'] = $gid;
			}
		}
		
		if ($setarr ['gid']) {
			$this->rightmessage ( L ( 'do_success' ), $_POST ['referer'], ($setarr), array (
					'showdialog' => 1,
					'showmsg' => true,
					'closetime' => 1 
			) );
		} else {
			$this->errormessage ( L ( 'do_failure' ), $_POST ['referer'], ($setarr), array (
					'showdialog' => 1,
					'showmsg' => true,
					'closetime' => 5 
			) );
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 删除个人小挂件
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	public function doWidgetDelete() {
		if (submitcheck ( 'deletesubmit' )) {
			$gid = intval ( $_POST ['gid'] );
			$msg = dsk_delete_widget ( $gid );
			
			if ($msg == 'success') {
				$this->rightmessage ( L ( 'do_success' ), $_POST ['referer'], array (
						'gid' => $gid 
				), array () );
			} else {
				$this->errormessage ( L ( 'do_failure' ) );
			}
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 创建文件夹
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	private function newfolder() {
		$desktop = ($_GET ['desktop']);
		$pfid = intval ( $_GET ['pfid'] );
		
		// 验证用户的ukey
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$arr = array ();
			$arr ['msg'] = 'refresh';
			echo json_encode ( $arr );
			exit ();
		}
		if ($this->space ['self']) {
			if ($arr = dsk_create_folder ( $this->mid, $pfid, $desktop )) {
				// 如果创建成功时要执行
				if ($pfid) {
					if ($folder = M ( "dsk_folder" )->field ( "ids,fid" )->where ( "fid='{$pfid}'" )->find ()) {
						if ($folder ['ids'])
							$ids = explode ( ',', $folder ['ids'] );
						else
							$ids = array ();
						$ids [] = $arr ['icoarr'] ['icoid'];
						M ( "dsk_folder" )->where ( "fid='{$folder[fid]}'" )->save ( array (
								'ids' => implode ( ',', $ids ) 
						) );
						if ($arr ['folderarr'] ['ids'])
							$arr ['folderarr'] ['ids'] = explode ( ',', $arr ['folderarr'] ['ids'] );
						else
							$arr ['folderarr'] ['ids'] = array ();
						$arr ['msg'] = 'success';
					} else {
						M ( "dsk_folder" )->where ( "fid='{$arr[folderarr][fid]}'" )->delete ();
						M ( "dsk_icos" )->where ( "icoid='{$arr[icoarr][icoid]}'" )->delete ();
						$arr = array ();
						$arr ['msg'] = 'target folder not exist!';
					}
				} else if ($desktop) {
					// 当不是navbar的时候，并且不是广场的时候
					if (strpos ( $desktop, 'sys_' ) !== false && strpos ( $desktop, 'sys_2' ) === false)
						$duid = 0;
					else
						$duid = $this->mid;
					if ($this->space ['self'] < 2 && strpos ( $desktop, 'sys_' ) !== false) {
						M ( "dsk_folder" )->where ( "fid='{$arr[folderarr][fid]}'" )->delete ();
						M ( "dsk_icos" )->where ( "icoid='{$arr[icoarr][icoid]}'" )->delete ();
						$arr = array ();
						$arr ['msg'] = L ( 'no_privilege' );
					} else {
						if ($config = M ( "dsk_userconfig" )->where ( "uid='$duid'" )->find ()) {
							$screenlist = unserialize ( stripslashes ( $config ['screenlist'] ) );
							if ($screenlist [$desktop]) {
								$screenlist [$desktop] ['icos'] [] = $arr ['icoarr'] ['icoid'];
								M ( "dsk_userconfig" )->where ( "uid='{$duid}'" )->save ( array (
										'screenlist' => addslashes ( serialize ( $screenlist ) ) 
								) );
								if ($arr ['folderarr'] ['ids'])
									$arr ['folderarr'] ['ids'] = explode ( ',', $arr ['folderarr'] ['ids'] );
								else
									$arr ['folderarr'] ['ids'] = array ();
								$arr ['msg'] = 'success';
							} else if (strpos ( $desktop, 'sys_2' ) !== false) {
								$navbarlist = array ();
								$navbarlist = unserialize ( stripslashes ( $config ['navbarlist'] ) );
								$navbarlist ['sys_2'] ['icos'] [] = $arr ['icoarr'] ['icoid'];
								M ( "dsk_userconfig" )->where ( "uid='{$duid}'" )->save ( array (
										'navbarlist' => addslashes ( serialize ( $navbarlist ) ) 
								) );
								if ($arr ['folderarr'] ['ids'])
									$arr ['folderarr'] ['ids'] = explode ( ',', $arr ['folderarr'] ['ids'] );
								else
									$arr ['folderarr'] ['ids'] = array ();
								$arr ['msg'] = 'success';
							} else {
								M ( "dsk_folder" )->where ( "fid='{$arr[folderarr][fid]}'" )->delete ();
								M ( "dsk_icos" )->where ( "icoid='{$arr[icoarr][icoid]}'" )->delete ();
								$arr = array ();
								$arr ['msg'] = 'target desktop not exist!';
							}
						}
					}
					
					/*
					 * 源代码 if($this->space['self']<2 && strpos($desktop,'sys_')!==false){ M("dsk_folder")->where("fid='{$arr[folderarr][fid]}'")->delete(); M("dsk_icos")->where("icoid='{$arr[icoarr][icoid]}'")->delete(); $arr=array(); $arr['msg']=L('no_privilege'); }else{ if($config=M("dsk_userconfig")->where("uid='$duid'")->find()){ $screenlist=unserialize(stripslashes($config['screenlist'])); if($screenlist[$desktop]) { $screenlist[$desktop]['icos'][]=$arr['icoarr']['icoid']; M("dsk_userconfig")->where("uid='{$duid}'")->save(array('screenlist'=>addslashes(serialize($screenlist)))); if($arr['folderarr']['ids']) $arr['folderarr']['ids']=explode(',',$arr['folderarr']['ids']); else $arr['folderarr']['ids']=array(); $arr['msg']='success'; }else{ M("dsk_folder")->where("fid='{$arr[folderarr][fid]}'")->delete(); M("dsk_icos")->where("icoid='{$arr[icoarr][icoid]}'")->delete(); $arr=array(); $arr['msg']='target desktop not exist!'; } } }
					 */
				}
			} else {
				$arr = array ();
				$arr ['msg'] = 'failure';
			}
		} else {
			$arr = array ();
			$arr ['msg'] = L ( 'not_login' );
		}
		
		echo json_encode ( $arr );
		exit ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 重命名方法
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-15
	 *         +----------------------------------------------------------
	 */
	private function rename() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$arr = array ();
			$arr ['msg'] = 'refresh';
			echo json_encode ( $arr );
			exit ();
		}
		$arr = array ();
		$icoid = intval ( $_GET ['icoid'] );
		$text = $_POST ['text'];
		$arr ['text'] = $text;
		if (! $this->space ['self']) {
			$arr ['msg'] = L ( 'no_privilege' );
			echo json_encode ( $arr );
			exit ();
		} else {
			if ($icoarr = M ( 'dsk_icos' )->field ( "oid,uid,type" )->where ( "icoid='{$icoid}'" )->find ()) {
				if ($icoarr ['uid'] == $this->mid || $this->space ['self'] > 1) {
					switch ($icoarr ['type']) {
						case 'folder' :
							if (M ( 'dsk_folder' )->where ( "fid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'fname' => addslashes ( $text ) 
							) )) {
								$arr ['dataname'] = 'fname';
							}
							break;
						case 'app' :
							M ( 'dsk_apps' )->where ( "appid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'appname' => addslashes ( $text ) 
							) );
							break;
						case 'blog' :
							M ( 'dsk_blog' )->where ( "blogid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'title' => addslashes ( $text ) 
							) );
							break;
						case 'link' :
							M ( 'dsk_link' )->where ( "lid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'title' => addslashes ( $text ) 
							) );
							break;
						case 'video' :
							M ( 'dsk_video' )->where ( "vid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'title' => addslashes ( $text ) 
							) );
							break;
						case 'music' :
							M ( 'dsk_music' )->where ( "mid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'title' => addslashes ( $text ) 
							) );
							break;
						case 'image' :
							M ( 'dsk_image' )->where ( "picid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'title' => addslashes ( $text ) 
							) );
							break;
						case 'attach' :
							M ( 'dsk_attach' )->where ( "qid='{$icoarr[oid]}' and uid='{$this->mid}'" )->save ( array (
									'filename' => addslashes ( $text ) 
							) );
							break;
					}
					M ( 'dsk_icos' )->where ( "icoid='{$icoid}'" )->save ( array (
							'name' => addslashes ( $text ) 
					) );
					$arr ['msg'] = 'success';
					echo json_encode ( $arr );
					exit ();
				} else {
					$arr ['msg'] = L ( 'no_privilege' );
					echo json_encode ( $arr );
					exit ();
				}
			} else {
				$arr ['msg'] = 'not exist icoid';
				echo json_encode ( $arr );
				exit ();
			}
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * ajax入口
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-3-18 下午1:58:19
	 *         +----------------------------------------------------------
	 */
	public function ajax() {
		$do = strtolower ( trim ( $_REQUEST ['do'] ) );
		switch ($do) {
			case 'userdetail' :
				$this->userDetail ();
				break;
			case 'widget' :
				$this->widget ();
				break;
			case 'edit' :
				$this->edit ();
				break;
			case 'newlink' :
				$this->newlink ();
				break;
			default :
				$this->$do ();
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * ajax获取桌面右上角的用户个人信息
	 * +----------------------------------------------------------
	 * 
	 * @author 小波
	 *         +----------------------------------------------------------
	 *         创建时间�?013-3-22 下午2:28:22
	 *         +----------------------------------------------------------
	 */
	private function userDetail() {
		// 个人资料完整度
		$where_str = "utypeid=0";
		if (empty ( $_SESSION ['ucInfo'] ['identitytype'] )) {
			$where_str .= ' or utypeid=' . intval ( $_SESSION ['ucInfo'] ['identitytype'] );
		}
		$_set_count = M ( 'user_set' )->where ( $where_str )->count ();
		$_set_profile = M ( 'user_profile' )->where ( 'uid=' . $this->mid )->limit ( 2 )->select (); // 潘信伍更改于2013年5月23日 11:15:25
		$_set_data = array ();
		foreach ( $_set_profile as $v ) {
			$_set_data = array_merge ( $_set_data, unserialize ( $v ['data'] ) );
		} // 此处如果数据为“空”也会计算在内，bug
		
		foreach ( $_set_data as $v ) {
			if ($v !== "") {
				$_set_data_count ++;
			}
		}
		$rate = floor ( ($_set_data_count / $_set_count) * 100 ) / 100 * 100;
		$this->assign ( 'userinfo_rate', $rate );
		$this->display ( 'userdetail' );
	}
	/**
	 * +----------------------------------------------------------
	 * 插件设置相关
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	private function widget() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$this->error ( L ( 'need_refresh' ) );
		}
		$gid = intval ( $_GET ['gid'] );
		$icoid = intval ( $_GET ['icoid'] );
		$widget = array ();
		if ($icoid) {
			$ico = M ( "dsk_icos" )->where ( "icoid='$icoid'" )->find ();
			switch ($ico ['type']) {
				case 'image' :
					$widget = array (
							'type' => 'image',
							'width' => $ico ['wwidth'],
							'height' => $ico ['wheight'],
							'url' => $ico ['url'] 
					);
					break;
				
				default :
					static $imgext = array (
							'jpg',
							'jpeg',
							'gif',
							'png',
							'bmp' 
					);
					static $videoext = array (
							'swf',
							'flv' 
					);
					$ext = strtolower ( substr ( strrchr ( $ico ['url'], '.' ), 1, 50 ) );
					$type = 'link';
					if (in_array ( $ext, $imgext ))
						$type = 'image';
					elseif (in_array ( $ext, $videoext ))
						$type = 'flash';
					$widget = array (
							'type' => $type,
							'width' => $ico ['wwidth'] ? $ico ['wwidth'] : 460,
							'height' => $ico ['wheight'] ? $ico ['wheight'] : 300,
							'url' => $ico ['url'] 
					);
					break;
			}
		}
		if ($gid)
			$widget = M ( "dsk_widget" )->where ( "gid='$gid'" )->find ();
		
		$this->assign ( $_GET );
		$this->assign ( 'widget', $widget );
		$this->display ( "widget" );
	}
	/**
	 * +----------------------------------------------------------
	 * 删除widget功能
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	private function widget_delete() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$this->error ( L ( 'need_refresh' ) );
		}
		$gid = intval ( $_GET ['gid'] );
		if ($gid)
			$widget = M ( "dsk_widget" )->where ( "gid='$gid'" )->find ();
		
		$this->assign ( $_GET );
		$this->assign ( 'widget', $widget );
		$this->display ( "widget_delete" );
	}
	/**
	 * +----------------------------------------------------------
	 * 右键新建快捷方式
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-17
	 *         +----------------------------------------------------------
	 */
	private function newlink() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$this->errormessage ( L ( 'need_refresh' ), '', '', array (
					'showdialog' => 1,
					'showmsg' => true,
					'closetime' => 3 
			) );
		}
		$container = trim ( $_GET ['container'] );
		
		$this->assign ( $_GET );
		$this->display ( 'newlink' );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 提交newlink页面
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-17
	 *         +----------------------------------------------------------
	 */
	public function doNewLink() {
		if (submitcheck ( 'newlinksubmit' )) {
			$link = trim ( $_POST ['link'] );
			// $link = dhtmlspecialchars(trim($_POST['link']));
			
			// �����ַ�Ϸ���
			if (! preg_match ( "/^(http|ftp|https|mms)\:\/\/.{4,300}$/i", $link ))
				$this->errormessage ( L ( 'href_illegal' ) );
			
			$ext = strtolower ( substr ( strrchr ( $link, '.' ), 1, 10 ) );
			static $imgext = array (
					'jpg',
					'jpeg',
					'gif',
					'png',
					'bmp' 
			);
			static $musicext = array (
					'mp3',
					'wma',
					'ram',
					'ra',
					'mid' 
			);
			static $videoext = array (
					'swf',
					'flv' 
			);
			static $videohost = array (
					'tudou.com',
					'youku.com',
					'56.com',
					'ku6.com' 
			);
			$isimage = in_array ( $ext, $imgext ) ? 1 : 0;
			$ismusic = in_array ( $ext, $musicext ) ? 1 : 0;
			$container = trim ( $_POST ['container'] );
			
			/*
			 * ��ͼƬʱ���� if($isimage){ if($data=linktoimage($link,$uid,intval($_GET['friend']),$container)){ if($data['error']) $this->errormessage($data['error']); $this->rightmessage(L('do_success'),$refer.'',$data,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } }elseif($ismusic){ if($data=linktomusic($link,$uid,intval($_GET['friend']),$container)){ if($data['error']) $this->errormessage($data['error']); $this->rightmessage(L('do_success'),$refer.'',$data,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } }else{ //��ͼ��Ϊ��Ƶ���� if($data=linktovideo($link,$uid,intval($_GET['friend']),$container)){ if($data['error']) $this->errormessage($data['error']); $this->rightmessage(L('do_success'),$refer.'',$data,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); }
			 */
			// ��Ϊ��ַ����
			if ($data = linktourl ( $link, $this->mid, intval ( $_POST ['friend'] ), $container )) {
				if ($data ['error'])
					$this->errormessage ( $data ['error'] );
				$this->rightmessage ( L ( 'do_success' ), $refer . '', $data, array (
						'showdialog' => 1,
						'showmsg' => true,
						'closetime' => 1 
				) );
			} else {
				$this->errormessage ( L ( 'network_error' ) );
			}
			// }
		}
	}
	/**
	 * +----------------------------------------------------------
	 * 编辑功能
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-14
	 *         +----------------------------------------------------------
	 */
	private function edit() {
		$ukey = trim ( $_GET ['ukey'] );
		if ($ukey != M ( 'dsk_userconfig' )->where ( "uid='{$this->mid}'" )->getField ( "`ukey`" )) {
			$this->errormessage ( L ( 'need_refresh' ), '', '', array (
					'showdialog' => 1,
					'showmsg' => true,
					'closetime' => 3 
			) );
		}
		$icoid = intval ( $_GET ['icoid'] );
		if (! $icoarr = dstripslashes ( M ( 'dsk_icos' )->where ( "icoid='{$icoid}'" )->find () )) {
			$this->errormessage ( L ( 'edit_ico_not_exist' ), '', '', array (
					'showdialog' => 1,
					'showmsg' => true,
					'closetime' => 3 
			) );
		}
		$data = $_GET;
		$data ['icoarr'] = $icoarr;
		$data ['type'] = $icoarr ['type'];
		switch ($data ['type']) {
			case 'folder' :
				// 编辑文件夹
				if (! $folder = M ( 'dsk_folder' )->where ( "fid='{$icoarr[oid]}'" )->find ()) {
					$this->errormessage ( L ( 'folder_not_exist' ), '', '', array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 3 
					) );
				}
				$data ['folder'] = $folder;
				break;
			case 'link' :
				// ��ȡĿ¼
				$parseurl = parse_url ( $icoarr ['url'] );
				$host = $parseurl ['host'];
				$host = preg_replace ( "/^www./", '', $host );
				$domainlist = array ();
				$query = M ( 'dsk_icon' )->where ( "domain='{$host}' and `check`>1" )->order ( "disp" )->findAll ();
				foreach ( $query as $value ) {
					$value ['pic'] = $_G ['setting'] ['attachurl'] . $value ['pic'];
					$domainlist [] = $value;
				}
				if (! $link = M ( 'dsk_link' )->where ( "lid='{$icoarr[oid]}'" )->find ()) {
					$this->errormessage ( L ( 'link_not_exist' ), '', '', array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 3 
					) );
				}
				$data ['domainlist'] = $domainlist;
				$data ['icoarr'] ['did'] = $link ['did'];
				$data ['link'] = $link;
				break;
			case 'app' :
				// 编辑应用
				if ($app = M ( 'dsk_apps' )->where ( "appid='{$icoarr[oid]}'" )->find ()) {
					if ($app ['uid'] == $uid) {
						$ismyapp = 1;
						// ��ȡӦ���г�����
						$appclass = array ();
						$query = M ( 'dsk_appclass' )->where ( "fupid=0" )->order ( "disp" )->findAll ();
						foreach ( $query as $value ) {
							$appclass [$value ['classid']] = $value;
						}
					}
				} else {
					$this->errormessage ( L ( 'app_not_exist' ), '', '', array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 3 
					) );
				}
				$data ['app'] = $app;
				break;
		}
		
		$this->assign ( $data );
		$this->display ( 'edit' );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 保存编辑后的结果
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-7-10
	 *         +----------------------------------------------------------
	 */
	public function doEdit() {
		$type = $_GET ['type'];
		$icoid = intval ( $_GET ['icoid'] );
		switch ($type) {
			case 'folder' :
				$icoarr = M ( 'dsk_icos' )->where ( "icoid='{$icoid}'" )->find ();
				$folder = array (
						'fname' => getstr ( ($_POST ['name']), 80 ),
						'ficon' => $_GET ['img'],
						'friend' => intval ( $_GET ['friend'] ) 
				);
				M ( "dsk_folder" )->where ( "fid='{$icoarr['oid']}'" )->save ( daddslashes ( $folder ) );
				$setarr = array (
						'name' => $folder ['fname'],
						'img' => $_GET ['img'],
						'friend' => intval ( $_GET ['friend'] ) 
				);
				
				if (M ( "dsk_icos" )->where ( "icoid='{$icoid}'" )->save ( daddslashes ( $setarr ) )) {
					$icoarr ['name'] = $setarr ['name'];
					$icoarr ['img'] = $setarr ['img'];
					$icoarr ['friend'] = $setarr ['friend'];
					$this->rightmessage ( L ( 'do_success' ), $refer . '', $icoarr, array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 1 
					) );
				}
				
				break;
			case 'app' :
				$icoarr = M ( 'dsk_icos' )->where ( "icoid='{$icoid}'" )->find ();
				$ismyapp = intval ( $_GET ['ismyapp'] );
				
				if ($ismyapp && $apparr = M ( 'dsk_apps' )->field ( "appid,appico,uid" )->where ( "appid='{$icoarr['oid']}' and uid='{$icoarr['uid']}'" )->find ()) {
					if (! preg_match ( "/^(http|ftp|https|mms)\:\/\/(.+?)/i", $apparr ['appico'] )) {
						$apparr ['appico1'] = replace_url ( $apparr ['appico'] );
					} else {
						$apparr ['appico1'] = $apparr ['appico'];
					}
					$app = array (
							'appname' => getstr ( $_POST ['name'], 80 ),
							'appdesc' => getstr ( trim ( $_POST ['appdesc'] ) ),
							'classid' => intval ( $_POST ['classid'] ),
							'appurl' => trim ( $_POST ['url'] ),
							'open' => intval ( $_POST ['open'] ),
							'width' => intval ( $_POST ['width'] ),
							'height' => intval ( $_POST ['height'] ) 
					);
					// ����Ӧ��ͼ��
					if ($apparr ['appico1'] != $_POST ['img']) {
						$appico = imagetolocal ( $_POST ['img'], 'appimg', $apparr ['appico'] );
					}
					if ($appico)
						$app ['appico'] = $appico;
					
					DB::update ( 'dsk_apps', daddslashes ( $app ), "appid='{$apparr[appid]}' and uid='{$appico[uid]}'" );
				}
				$setarr = array (
						'name' => getstr ( $_POST ['name'], 80 ),
						'img' => $_GET ['img'],
						'friend' => intval ( $_GET ['friend'] ),
						'open' => intval ( $_POST ['open'] ),
						'wwidth' => intval ( $_POST ['width'] ),
						'wheight' => intval ( $_POST ['height'] ) 
				);
				if ($ismyapp) {
					$setarr ['url'] = trim ( $_POST ['url'] );
					$icoarr ['url'] = $setarr ['url'];
				}
				if (DB::update ( 'dsk_icos', daddslashes ( $setarr ), " icoid='{$icoid}'" )) {
					$icoarr ['name'] = $_GET ['name'];
					$icoarr ['img'] = $_GET ['img'];
					$icoarr ['friend'] = intval ( $_GET ['friend'] );
					$icoarr ['wwidth'] = intval ( $_GET ['width'] );
					$icoarr ['wheight'] = intval ( $_GET ['height'] );
					$icoarr ['open'] = intval ( $_GET ['open'] );
					showmessage ( 'do_success', $refer . '', $icoarr, array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 1 
					) );
				}
				break;
			case 'link' :
				$icoarr = DB::fetch_first ( "select ico.*,link.did from " . DB::table ( 'dsk_icos' ) . " ico
													  LEFT JOIN " . DB::table ( 'dsk_link' ) . " link ON ico.oid=link.lid
    																  where icoid='{$icoid}'" );
				$parseurl = parse_url ( $icoarr ['url'] );
				$host = $parseurl ['host'];
				$host = preg_replace ( "/^www./", '', $host );
				// �����û�������
				$relationship = intval ( $_POST ['relationship'] );
				if ($relationship && $icoarr ['img'] !== $_POST ['img']) { // �û�����ͼ���� ������ͼ����ַ����
					$usericon = array (
							'uid' => $space ['uid'],
							'domain' => $host,
							'pic' => $_POST ['img'],
							'dateline' => $_G ['timestamp'],
							'pdid' => intval ( $_POST ['did'] ) 
					);
					$did = DB::result ( DB::query ( "SELECT did FROM " . DB::table ( 'dsk_usericon' ) . " where domain='{$host}' AND uid='{$space[uid]}'" ) );
					if ($did) {
						DB::update ( 'dsk_usericon', daddslashes ( $usericon ), "did='{$did}'" );
					} else {
						DB::insert ( 'dsk_usericon', daddslashes ( $usericon ) );
					}
				}
				$link = array (
						'title' => getstr ( ($_POST ['name']), 80 ),
						'img' => $_POST ['img'],
						'friend' => intval ( $_POST ['friend'] ),
						'did' => intval ( $_POST ['did'] ) 
				);
				DB::update ( 'dsk_link', daddslashes ( $link ), " lid='{$icoarr[oid]}'" );
				$setarr = array (
						'img' => $_POST ['img'],
						'wwidth' => intval ( $_POST ['width'] ),
						'wheight' => intval ( $_POST ['height'] ),
						'open' => intval ( $_POST ['open'] ),
						'name' => $link ['title'],
						'friend' => intval ( $_POST ['friend'] ) 
				);
				if (DB::update ( 'dsk_icos', daddslashes ( $setarr ), " icoid='{$icoid}'" )) {
					$icoarr ['name'] = $link ['title'];
					$icoarr ['img'] = $link ['img'];
					$icoarr ['friend'] = $link ['friend'];
					$icoarr ['wwidth'] = intval ( $_POST ['width'] );
					$icoarr ['wheight'] = intval ( $_POST ['height'] );
					$icoarr ['open'] = intval ( $_POST ['open'] );
					
					// ����������ʹ����
					if ($icoarr ['did'] && $icoarr ['did'] != $link ['did']) {
						if ($icon = DB::fetch_first ( "select * from " . DB::table ( 'dsk_icon' ) . " where did='{$icoarr[did]}'" )) {
							if ($icon ['check'] < 2 && $icon ['copys'] < 2) {
								@unlink ( DISCUZ_ROOT . './' . $icon ['pic'] );
								DB::delete ( 'dsk_icon', "did='{$icoarr[did]}'" );
							} else {
								DB::update ( 'dsk_icon', array (
										'copys' => $icon ['copys'] - 1 
								), "did='{$icoarr[did]}'" );
							}
						}
					}
					if ($link ['did'] && $icoarr ['did'] != $link ['did'])
						DB::query ( "update " . DB::table ( 'dsk_icon' ) . " SET copys=copys+1 where did='{$link[did]}'" );
					showmessage ( 'do_success', $refer . '', $icoarr, array (
							'showdialog' => 1,
							'showmsg' => true,
							'closetime' => 1 
					) );
				}
				
				break;
			/*
			 * case 'image': $icoarr=DB::fetch_first("select * from ".DB::table('dsk_icos')." where icoid='{$icoid}'"); $image=array( 'title'=>getstr(($_POST['name']), 80), 'friend'=>intval($_GET['friend']), ); DB::update('dsk_image',daddslashes($image)," picid='{$icoarr[oid]}'"); $setarr=array( 'name'=>$image['title'], 'friend'=>intval($_GET['friend']), ); if(DB::update('dsk_icos',daddslashes($setarr)," icoid='{$icoid}'")){ $icoarr['name']=$setarr['name']; $icoarr['friend']=intval($_GET['friend']); showmessage('do_success',$refer.'',$icoarr,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } break; case 'video': $icoarr=DB::fetch_first("select * from ".DB::table('dsk_icos')." where icoid='{$icoid}'"); $video=array( 'title'=>getstr(($_POST['name']), 80), 'friend'=>intval($_GET['friend']), ); DB::update('dsk_video',daddslashes($video)," vid='{$icoarr[oid]}'"); $setarr=array( 'name'=>$video['title'], 'friend'=>intval($_GET['friend']), ); if(DB::update('dsk_icos',daddslashes($setarr)," icoid='{$icoid}'")){ $icoarr['name']=$setarr['name']; $icoarr['friend']=intval($_GET['friend']); showmessage('do_success',$refer.'',$icoarr,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } break; case 'music': $icoarr=DB::fetch_first("select * from ".DB::table('dsk_icos')." where icoid='{$icoid}'"); $music=array( 'title'=>getstr(($_POST['name']), 80), 'friend'=>intval($_GET['friend']), ); DB::update('dsk_music',daddslashes($music)," mid='{$icoarr[oid]}'"); $setarr=array( 'name'=>$music['title'], 'friend'=>intval($_GET['friend']), ); if(DB::update('dsk_icos',daddslashes($setarr)," icoid='{$icoid}'")){ $icoarr['name']=$setarr['name']; $icoarr['friend']=intval($_GET['friend']); showmessage('do_success',$refer.'',$icoarr,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } break; case 'attach': $icoarr=DB::fetch_first("select * from ".DB::table('dsk_icos')." where icoid='{$icoid}'"); $attach=array( 'filename'=>getstr(($_POST['name']), 80), 'friend'=>intval($_GET['friend']), ); DB::update('dsk_attach',daddslashes($attach)," qid='{$icoarr[oid]}'"); $setarr=array( 'name'=>$attach['filename'], 'friend'=>intval($_GET['friend']), ); if(DB::update('dsk_icos',daddslashes($setarr)," icoid='{$icoid}'")){ $icoarr['name']=$setarr['name']; $icoarr['friend']=intval($_GET['friend']); showmessage('do_success',$refer.'',$icoarr,array('showdialog'=>1, 'showmsg' => true, 'closetime' => 1)); } break;
			 */
		}
		
		include template ( 'common/header_ajax' );
		echo "<script type=\"text/javascript\" reload=\"1\">";
		echo "hideWindow('" . $_GET ['handlekey'] . "');";
		echo "</script>";
		include template ( 'common/footer_ajax' );
		exit ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 资源管理器
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-15
	 *         +----------------------------------------------------------
	 */
	public function explorer() {
		$do = empty ( $_GET ['do'] ) ? $_POST ['do'] : $_GET ['do'];
		// 判断是否是外部提交
		if (! empty ( $do )) {
			$this->$do ();
			exit ();
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 文件管理器
	 * +----------------------------------------------------------
	 * 
	 * @author 小波 2013-6-15
	 *         +----------------------------------------------------------
	 */
	private function filemanage() {
		$winid = $_GET ['winid'];
		$sid = empty ( $_GET ['id'] ) ? 0 : $_GET ['id'];
		$icoid = intval ( $_GET ['icoid'] );
		$data = array ();
		$data1 = array ();
		list ( $prex, $id, $fuid ) = explode ( '-', $sid );
		$folderdata = array ();
		$folderids = array ();
		if ($prex == 'f') {
			$container = 'icosContainer_folder_' . $id;
			$arr = array ();
			$icos = array ();
			
			if ($folder = M ( "dsk_folder" )->where ( "fid='{$id}'" )->find ()) {
				$open_p = 1;
				if ($folder ['friend'] == 1) {
					if (! $this->space ['self'])
						$open_p = 0;
				} elseif ($folder ['friend'] == 2) {
					if (! $this->space ['self'] && ! $this->space ['isfriend'])
						$open_p = 0;
				}
				if ($open_p) {
					$folder ['opened'] = 1;
					if ($folder ['ids']) {
						$icos = explode ( ',', $folder ['ids'] );
					} else {
						$icos = array ();
					}
					$folder ['ids'] = $icos;
					if ($icoid)
						$icos [] = $icoid;
					if ($icos) {
						
						$query = M ( "dsk_icos" )->where ( "icoid IN (" . dimplode ( $icos ) . ")" )->findAll ();
						foreach ( $query as $value ) {
							$value ['fsize'] = formatsize ( $value ['size'] );
							$value ['ftype'] = getFileTypeName ( $value ['type'], $value ['ext'] );
							$value ['fdateline'] = date ( "Y-m-d", $value ['dateline'] );
							$open_p = 1;
							if ($value ['friend'] == 1) {
								if (! $this->space ['self'])
									$open_p = 0;
							} elseif ($value ['friend'] == 2) {
								if (! $this->space ['self'] && ! $this->space ['isfriend'])
									$open_p = 0;
							}
							if (! $open_p) {
								$value ['img'] = C ( 'APP_PUBLIC_PATH' ) . '/images/default_ys/' . $value ['type'] . '.png';
							}
							$arr [$value ['icoid']] = $value;
							if ($value ['type'] == 'folder')
								$folderids [] = $value ['oid'];
						}
						// ������Ŀ¼��˳������
						foreach ( $icos as $value ) {
							if ($value != $icoid)
								$data [$value] = $arr [$value];
							else
								$data1 = $arr [$icoid];
						}
					}
					$folderdata [$folder ['fid']] = $folder;
					
					// Ŀ¼���
					if ($folderids) {
						$query = M ( "dsk_folder" )->where ( "fid IN (" . dimplode ( $folderids ) . ")" )->findAll ();
						foreach ( $query as $value ) {
							if ($value ['ids'])
								$value ['ids'] = explode ( ',', $value ['ids'] );
							else
								$value ['ids'] = array ();
							$folderdata [$value ['fid']] = $value;
						}
					}
				}
				$json_folder = json_encode ( $folderdata );
				$json_data1 = json_encode ( $data1 );
			}
		} elseif ($prex == 'd') {
			if ($config_u = M ( "dsk_userconfig" )->where ( "uid='$fuid'" )->findAll ()) {
				// 获取用户桌面上的图标
				$screenlist_u = unserialize ( stripslashes ( $config_u ['screenlist'] ) );
				$icos = $screenlist_u [$id] ['icos'];
			}
			$container = 'icosContainer_body_' . $id;
			$folderids = array ();
			if ($icos) {
				$query = M ( "dsk_icos" )->where ( "icoid IN (" . dimplode ( $icos ) . ")" )->findAll ();
				foreach ( $query as $value ) {
					$value ['fsize'] = formatsize ( $value ['size'] );
					$value ['ftype'] = getFileTypeName ( $value ['type'], $value ['ext'] );
					$value ['fdateline'] = date ( "Y-m-d", $value ['dateline'] );
					$open_p = 1;
					if ($value ['friend'] == 1) {
						if (! $this->space ['self'])
							$open_p = 0;
					} elseif ($value ['friend'] == 2) {
						if (! $this->space ['self'] && ! $this->space ['isfriend'])
							$open_p = 0;
					}
					if (! $open_p) {
						$value ['img'] = C ( 'APP_PUBLIC_PATH' ) . '/images/default_ys/' . $value ['type'] . '.png';
					}
					$arr [$value ['icoid']] = $value;
					
					if ($value ['type'] == 'folder')
						$folderids [] = $value ['oid'];
				}
			}
			// ��������˳������
			foreach ( $icos as $icoid ) {
				$data [$icoid] = $arr [$icoid];
			}
			// Ŀ¼���
			$folderdata = array ();
			if ($folderids) {
				$query = M ( "dsk_folder" )->where ( "fid IN (" . dimplode ( $folderids ) . ")" )->findAll ();
				foreach ( $query as $value ) {
					if ($value ['ids'])
						$value ['ids'] = explode ( ',', $value ['ids'] );
					else
						$value ['ids'] = array ();
					$folderdata [$value ['fid']] = $value;
				}
			}
			$json_folder = json_encode ( $folderdata );
		} elseif ($prex == 'type') {
			$container = 'icosContainer_type_' . $id;
		} elseif ($prex == 'friend') {
			$container = 'icosContainer_frind_' . $id;
		} elseif ($prex == 'alluser') {
			$container = 'icosContainer_alluser_' . $id;
		}
		
		$json = json_encode ( $data );
		
		$this->assign ( "json_folder", $json_folder );
		$this->assign ( "json_data1", $json_data1 );
		$this->assign ( "json", $json );
		$this->assign ( "sid", $sid );
		$this->assign ( "winid", $winid );
		$this->assign ( "container", $container );
		$this->display ( "filemanage" );
	}
}
?>