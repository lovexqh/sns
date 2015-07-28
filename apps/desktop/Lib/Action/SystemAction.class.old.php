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
 * 创建时间�?2013-3-18 下午1:55:54
 +------------------------------------------------------------------------------
 */
class SystemAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();

	}

	/**
	 +----------------------------------------------------------
	 * 个人系统设置索引
	 +----------------------------------------------------------
	 * @author 小波 2013-6-15
	 +----------------------------------------------------------
	 */
	public function index(){
		$op = empty($_GET['op']) ? $_POST['op'] : $_GET['op'];
		//判断是否是外部提交
		if (!empty($op)) {
			$this->$op();
			exit();
		}
	}
	/**
	 +----------------------------------------------------------
	 * ajax入口
	 +----------------------------------------------------------
	 * @author 小波
	 +----------------------------------------------------------
	 * 创建时间�?013-3-18 下午1:58:19
	 +----------------------------------------------------------
	 */
	public function ajax(){
		$do = strtolower(trim($_REQUEST['do']));
		switch ($do){
			case 'userdetail':
				$this->userDetail();
				break;
			default:
				$this->display();
		}
    }
    
    /**
     +----------------------------------------------------------
     * ajax获取桌面右上角的用户个人信息
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间�?013-3-22 下午2:28:22
     +----------------------------------------------------------
     */
    private function userDetail(){
    	$this->display('userdetail');
    }
    
    /**
     +----------------------------------------------------------
     * 初始化系统应用数据
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-19 上午10:32:48
     +----------------------------------------------------------
     */
    private function initApps(){
    	global $ts;
    	//$resultarr是从app表里面调出符合条件的应用信息作为原来的系统信息
    	$resultarr = M('app')->where("status='1' and FIND_IN_SET('1',group_id) and app_name <> 'desktop'")->order('display_order ASC')->findAll();
    	//$icosarr这个是从桌面对应的应用里面来显示，系统默认的应用什么
    	$icosarr = M('dsk_icos')->where("uid='-1' and idtype='app'")->findAll();
    	foreach ($icosarr as $icos)
    	{ 
    		$icosoids[] = intval($icos['oid']); 
    	}
    	/*
    	 * 这个的作用是：
    	 * 把从原来的app表里面调出的app与桌面的app进行对比，
    	 * 如果桌面的app里面没有桌面对应的，就从$resultarr数组里面移除这个应用
    	 * 
    	 * 这个地方 ，我觉得不应该做移除的操作，在原app里面应该是默认的应用，在桌面app里面也应该是默认 的应用
    	 * 程序没有修改，还是移除
    	 */
    	foreach ($resultarr as $k=>$app){
    		if ( in_array(intval($app['app_id']), $icosoids )){ 
    			unset( $resultarr[$k] ); 
    			unset( $icosoids[array_search( $app['app_id'], $icosoids )] );
    		}
    	}
    	
    	if (!empty($icosoids)) {
    		$map['oid'] = Array('in',$icosoids);
    		$map['idtype'] = 'app';
    		//删除系统默认应用
    		M('dsk_icos')->where($map)->delete();
    		//删除当前用户桌面已存在的系统应用
    	}
    	
    	foreach ($resultarr as $app){
	    	$icoarr=array(
	    			'uid'=>'-1',
	    			'username'=>'admin',
	    			'oid'=>$app['app_id'],
	    			'name'=>$app['app_alias'],
	    			'url'=>U($app['app_name'].'/'.$app['ap_entry']),
	    			'img'=>$app['icon_url'],
	    			'wwidth'=> empty($app['width']) ? 1034 : $app['width'],
	    			'wheight'=> empty($app['height']) ? 600 : $app['height'],
	    			'open'=> empty($app['open']) ? 0 : $app['open'],
	    			'haveflash'=> empty($app['haveflash']) ? 0 : $app['haveflash'],
	    			'idtype'=>empty($app['idtype']) ? 'app' : $app['idtype'],
	    			'typeid'=> empty($app['typeid']) ? 0 : $app['typeid'],
	    			'havetask'=> empty($app['havetask']) ? 1 : $app['havetask'],
	    			'isshow'=> empty($app['isshow']) ? 1 : $app['isshow'],
	    			'titlebuttons'=>empty($app['titlebuttons']) ? 'max,min,close' : $app['titlebuttons'],$app['titlebuttons'],
	    			'desktop'=>1,
	    			'notdelete'=> 1==$app['group_id'] ? 1 : 0,
	    			'type'=>'app',
	    			'size'=>0,
	    			'ext'=>'',
	    			'friend'=>0,
	    			'dateline'=>time()
	    	);
	    	//将老系统中的系统应用转入桌面系统表中
	    	$returnarr[]=M('dsk_icos')->add($icoarr);
    	}
    	
    	//已安装的应用
    	$install_apps = $ts['install_apps'];
    	$system_apps = getuser_apps();
    	
    	//过滤系统应用
    	foreach ($install_apps as $key=>$app){
    		if ( !empty( $system_apps[$app['app_id']] ) && (strpos('1,',$app['group_id']) || $app['group_id'] == 1 )) {
    			
    		}else{
    			unset($install_apps[$key]);
    		}
    	}
    	
    	//得到不是默认应用的列表信息
    	$icosarr = M('dsk_icos')->where("uid in (-1,".$this->mid.") and idtype='app'")->select();
    	foreach ($icosarr as $icos){
    		$icosresult[$icos['oid']] = $icos;
    	}
    	/*
    	 * 同步当前登录用户的老系统的安装的应用
    	 * 里面有个$this->mid
    	 */
    	foreach ($install_apps as $app){
    		if( empty($icosresult[$app['app_id']]) ){
    			$icoarr=array(
    					'uid'=>$this->mid,
    					'username'=>getUserName($this->mid),
    					'oid'=>$app['app_id'],
    					'name'=>$app['app_alias'],
    					'url'=>U($app['app_name'].'/'.$app['ap_entry']),
    					'img'=>$app['icon_url'],
    					'wwidth'=> empty($app['width']) ? 1034 : $app['width'],
    					'wheight'=> empty($app['height']) ? 600 : $app['height'],
    					'open'=> empty($app['open']) ? 0 : $app['open'],
    					'haveflash'=> empty($app['haveflash']) ? 0 : $app['haveflash'],
    					'idtype'=>empty($app['idtype']) ? 'app' : $app['idtype'],
    					'typeid'=> empty($app['typeid']) ? 0 : $app['typeid'],
    					'havetask'=> empty($app['havetask']) ? 1 : $app['havetask'],
    					'isshow'=> empty($app['isshow']) ? 1 : $app['isshow'],
    					'titlebuttons'=>empty($app['titlebuttons']) ? 'max,min,close' : $app['titlebuttons'],$app['titlebuttons'],
    					'desktop'=>1,
    					'notdelete'=>0,
    					'type'=>'app',
    					'size'=>0,
    					'ext'=>'',
    					'friend'=>0,
    					'dateline'=>$app['ctime']
    			);
    			$returnarr[]=M('dsk_icos')->add($icoarr);
    			
    		}
    		else{
    			unset($icosresult[$app['app_id']]);
    		}
    	}
    	//将在旧系统中删除的应用数据保存成新桌面的id列表
    	foreach ($icosresult as $icos){
    		$outicos[] = $icos['icoid'];
    	}
    	//获取当前桌面系统中已存在的应用
    	if($config_u=M('dsk_userconfig')->where("uid='{$this->mid}'")->find()){
    		$screenlist=unserialize(stripslashes($config_u['screenlist']));
    		foreach ($screenlist as $key=>$value){
    			foreach ($value['icos'] as $k=>$v){
    				if (in_array($v,$outicos)) {
    					//删除新桌面系统中多余的应用
    					unset($screenlist[$key]['icos'][$k]);
    				}
    			}
    		}
    		M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('screenlist'=>addslashes(serialize($screenlist))));
    	}
    	
    	return $returnarr;
    }
    
    /**
     +----------------------------------------------------------
     * 获取系统json数据
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间�?013-3-19 下午2:02:47
     +----------------------------------------------------------
     */
    public function json(){
    	$do = $_GET['do'];
    	switch ($do){
    		case 'save': 	//保存设置方法
    			$this->save();
    			break;
    		case 'deleteIco': 	//删除应用方法
    			$this->deleteIco();
    			break;
    		default:
    			$this->jsonInit();
    	}
    }
    
    /**
     +----------------------------------------------------------
     * 系统JSON字符串初始化
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间�?013-3-21 上午10:52:39
     +----------------------------------------------------------
     */
    public function jsonInit(){
    	global $ts;
    	$data=array();
    	//有新应用时初始化桌面系统已安装应用表
    	//$this->initApps();
    	
    	//获取系统配置信息
    	if($this->setting['dsk_sysconfig']){
    		$sysconfig=dstripslashes(unserialize(stripslashes($this->setting['dsk_sysconfig'])));
    	}else{
    		$sysconfig=get_config();
    	}
    	if(intval($sysconfig['userscreennum'])<1){
    		$sysconfig['userscreennum']=1;
    	}
    	//设置ucenter地址
    	$sysconfig['ucenterurl'] = UC_API;
    	unset($sysconfig['siteuniqueid']);
    	$data['sysconfig']=$sysconfig;
    	
    	//获取图标视图配置
    	if($this->setting['dsk_iconview']){
    		$iconview=dstripslashes(unserialize(stripslashes($this->setting['dsk_iconview'])));
    	}else{
    		$result = M('dsk_iconview')->where('`avaliable`>0')->order('disp')->findAll();
    		$iconview = array();
    		foreach ($result as $value){
    			$iconview[$value['id']]=$value;
    		}
    	}
    	$data['iconview']=$iconview;
    	
		//貌似是要获取插件信息
    	$plugins=array();
    	$pluginappids=array();
    	$result = M('dsk_plugin')->where('`avaliable`>0')->findAll();
    	foreach ($result as $value){
    		if($value['datatype']) $value['datatype']=explode(',',$value['datatype']);
    		else $value['datatype']=array();
    		$plugins[$value['pluginid']]=dstripslashes($value);
    	}
    	
    	//获取用户的配置信息，以及桌面图标文件等
    	$navids_0=array();
    	$docklist_0=$screenlist_0=array();
    	
    	//获取系统预定义的用户配置模板
    	if($config_0=M('dsk_userconfig')->where("uid='0'")->find()){
    		$screenlist_0=unserialize(stripslashes($config_0['screenlist']));
    		$docklist_0=explode(',',$config_0['docklist']);
    		$data['iconpositions_0']=unserialize(stripslashes($config_0['iconpositions']));
    	}else{
    		//我勒个去，这里的注释都乱码了
    		//好像是获取nav条上的数
			//如果获取不到系统预定义的系统模板的操作
    		if($this->setting['dsk_nav']){
    			$navbar=dstripslashes(unserialize(stripslashes($this->setting['dsk_nav'])));
    		}else{
    			$result = M('dsk_navbar')->where('`avaliable`>0')->order('disp')->findAll();
    			$iconview = array();
    			foreach ($result as $value){
    				//重新格式化url 孙晓波于2013年6月7日添加
    				if (strrpos($value['navurl'], 'http://') !== 0 && strrpos($value['navurl'], 'https://') !== 0) {
    					$value['navurl'] = getSiteUrl() . __ROOT__ . '/' . $value['navurl'];
    				}
    				$navbar[$value['navid']]=$value;
    			}
    		}
    		foreach($navbar as $value){
    			if($value['navicon']) $value['navicon']=$this->setting['attachurl'].$value['navicon'];
    			$value['navid']='sys_'.$value['navid'];
    			$screenlist_0[$value['navid']]['config']=$value;
    			$screenlist_0[$value['navid']]['icos']=array();
    			$screenlist_0[$value['navid']]['wins']=array();
    			$screenlist_0[$value['navid']]['widgets']=array();
    		}
    		$setarr=array(
    				'uid'=>0,
    				'username'=>'',
    				'docklist'=>'',
    				'screenlist'=>serialize($screenlist_0),
    				'iconpositions'=>serialize(array()),
    				'dateline'=>time(),
    		);
    		M('dsk_userconfig')->add($setarr);
    	}
    	//获取共公的桌面扩展nav
    	if($this->setting['dsk_nav']){
    		$navbar=dstripslashes(unserialize(stripslashes($this->setting['dsk_nav'])));
    	}else{
    		$result = M('dsk_navbar')->where('`avaliable`>0')->order('disp')->findAll();
    		$navbar = array();
    		foreach ($result as $value){
    			//重新格式化url 孙晓波于2013年6月7日添加
    			if (strrpos($value['navurl'], 'http://') !== 0 && strrpos($value['navurl'], 'https://') !== 0) {
    				$value['navurl'] = getSiteUrl() . __ROOT__ . '/' . $value['navurl'];
    			}
    			$navbar[$value['navid']]=$value;
    		}
    	}
    	$needsave_screenlist_0=0;
    	if(count($screenlist_0)<count($navbar)){
    		$needsave_screenlist_0=1;
    	} 
    	foreach($navbar as $value){
    		if($value['navicon']) $value['navicon'] =$this->setting['attachurl'].$value['navicon'];
    		$value['navid']='sys_'.$value['navid'];
    		$screenlist_0[$value['navid']]['config']=$value;
    		if(!$screenlist_0[$value['navid']]['icos']) $screenlist_0[$value['navid']]['icos']=array();
    		if(!$screenlist_0[$value['navid']]['wins']) $screenlist_0[$value['navid']]['wins']=array();
    		if(!$screenlist_0[$value['navid']]['widgets']) $screenlist_0[$value['navid']]['widgets']=array();
    		$navids_0[]=$value['navid'];
    	}
    	if($needsave_screenlist_0){
    		M('dsk_userconfig')->where("uid='0'")->save(array('screenlist'=>addslashes(serialize($screenlist_0))));
    	}
    	$allicoids_0=array();
    	$gids=array();
    	foreach($screenlist_0 as $key =>$desktop){
    		if(!in_array($key,$navids_0)) {
    			unset($screenlist_0[$key]);
    			continue;
    		}
    		if($desktop['config']['isdefault']){
    			$data['currentDesktop']=$screenlist_0['current']?$screenlist_0['current']:$key;
    		} 
   			$tempicos=array();
    		foreach($desktop['icos'] as $icoid =>$value){
    			$allicoids_0[]=$value;
    			$tempicos[]=$value;
    		}
   			$screenlist_0[$key]['icos']=$tempicos;
    		foreach($desktop['wins'] as $icoid=>$value){
    			$allicoids_0[]=$value['icoid'];
    		}
    		foreach($desktop['widgets'] as $icoid=>$value){
    			$gids[]=$value['gid'];
    		}
    	}
    	/*
    	 * 这里面的 $allicoids_0 = array(),$docklist_0 = array(0=>array())
    	 */
    	if(!$this->mid)	$allicoids_0=array_merge($allicoids_0,$docklist_0);
    	
    	$navids_u=array();
    	$docklist_u=$screenlist_u=array();
    	/**
    	 * $this->mid = lhyfe@sohu.com =  120
    	 * 下面是这个是当用户登录之后 进行的操作
    	 */
    	if($this->mid){	
    		//如果是当前登录的用户
    		/*
    		 * $config_u = 是从dsk_userconfig表里面找uid=120的信息，
    		 * 这里面我把表里面的记录删除了，也就是 $config_u = null
    		 * $config_u = 这个是dsk_userconfig里面的记录，
    		 * 里面是用字符串的形式记录着用户的基本配置信息
    		 */
    		$config_u=M('dsk_userconfig')->where("uid='{$this->mid}'")->find();
    		if($config_u){
    			/*
    			 * $config_u就是用户的基本配置信息，现在找到了，下面把他还原成数组的形式
    			 * //获取用户的个人桌面图标
    			 */
    			$screenlist_u=unserialize(stripslashes($config_u['screenlist']));
    			/*
    			 * $screenlist_u就是用户屏幕的基本配置的数组信息，主要是屏幕的基本信息
    			 * 注意：这里面的icos现在初始化的时候，是空的，没有内容，但是桌面上还是显示7个应用了
    			 * 具体怎么处理的，下面看一下
    			 */
    			foreach($screenlist_u as $key=>$value){
    				$navids_u[]="$key";
    			}
    			/*
    			 * $navids_u = Array( [0] => 1,[1] => 2,[2] => 3,[3] => 4,[4] => 5)
    			 * 这种形式的，主要是用于显示5个屏幕的ids
    			 */
    			/*
    			 * 首次初始化用户时获取系统默认应用(新添加)
    			 * $needsave_screenlist默认是0，如果是1的话，就是初次使用，进行相应的操作
    			 */
    			$needsave_screenlist=0;
    			/*
    			 * 得到用户要显示的屏幕数量，循环显示出屏幕
    			 * 初始化一定数量的屏幕
    			 * 判断原来的屏幕与新屏幕个数
    			 * 这个地方里面的$screenlist_u['icos']是个空数组
    			 * 注意这里面的，有个判断$needsave_screenlist及in_array
    			 * 上面已经得到用户自定义配置里面的$navids_u，这个变量了
    			 * 在下面做一下判断 ，如果系统那边的桌面数量修改了，就初始化系统添加的新桌面
    			 * 同时 $needsave_screenlist = 1，为下面的更新用户配置到数据库做准备
    			 */
    			for($i=1;$i<=$sysconfig['userscreennum'];$i++){
    				if(!in_array("$i",$navids_u)) {
    					$navids_u[]="$i";
    					$screenlist_u[$i]['config']=array(
    							'navid'=>$i,
    							'navname'=>'桌面'.$i,
    							'type'=>'desktop',
    							'autolist'=>$sysconfig['autolist'],
    							'marginleft'=>$sysconfig['marginleft'],
    							'margintop'=>$sysconfig['margintop'],
    							'marginright'=>$sysconfig['marginright'],
    							'marginbottom'=>$sysconfig['marginbottom'],
    							'iconview'=>$sysconfig['iconview'],
    							'iconposition'=>$sysconfig['iconposition'],
    							'dockshow'=>$sysconfig['dockshow'],
    							'topbarshow'=>$sysconfig['topbarshow']
    					);
    					$screenlist_u[$i]['icos']=array();
    					$screenlist_u[$i]['wins']=array();
    					$screenlist_u[$i]['widgets']=array();
    					$needsave_screenlist=1;
    				}
    			}
    			/*
    			 * 这个里面的$sysconfig就是应用的配置信息，应该是从表ts_dsk_config表里面调用出来的
    			 * 下面这个函数的操作就是为了删除比配置中的屏幕数量多的屏幕
    			 */
    			foreach($screenlist_u as $key=>$value){
    				if($key>$sysconfig['userscreennum']) {
    					/*
    					 * desktop_move_userdata
    					 * 函数的作用：是为了，把多于规定桌面数量的桌面里面的图标，插件，wins合并
    					 */
    					$screenlist_u[$sysconfig['userscreennum']]=desktop_move_userdata($key,$this->mid,$sysconfig['userscreennum']);
    					unset($screenlist_u[$key]);
    					foreach($navids_u as $navid =>$value){
    						if($navid==$key) unset($navids_u[$key]);
    					}
    				}
    			}
    			/*
    			 * 这个应该是界面的配置信息吧，具体这里不说了，就是赋值的操作
    			 */
    			foreach($screenlist_u as $key=>$value){
    				$screenlist_u[$key]['config']['marginleft']=$sysconfig['marginleft'];
    				$screenlist_u[$key]['config']['margintop']=$sysconfig['margintop'];
    				$screenlist_u[$key]['config']['marginright']=$sysconfig['marginright'];
    				$screenlist_u[$key]['config']['marginbottom']=$sysconfig['marginbottom'];
    			}
    			/*
    			 * 这个应该是下面的dock的显示操作的
    			 */
    			$docklist_u=explode(',',$config_u['docklist']);
    			$data['iconpositions_u']=unserialize(stripslashes($config_u['iconpositions']));
    			
    			//echo $needsave_screenlist.'-----';
    			/*
    			 * $needsave_screenlist = 1的时候，有一种情况
    			 * 后台规定的屏幕数量发生的变化时，这里面要把新的系统配置更新到用户配置里面
    			 */
    			$needsave_screenlist = 1;
    			if($needsave_screenlist){
    				//首次初始化用户时获取系统默认应用(新添加)
    				$icos_u=M('dsk_icos')->field('icoid')->where("uid='{$this->mid}'")->findAll();
    				//在第一个屏幕也就是默认的屏幕上添加新的图标
    				foreach ($icos_u as $icos){
    					$screenlist_u[1]['icos'][] = $icos['icoid'];
    				}
    				//Rickeryu
    				//去除重复的内容
    				$screenlist_u[1]['icos'] = array_unique($screenlist_u[1]['icos']);
    				/*
    				 * 在这里面输出的$screenlist_u正常
    				 * icos里面只有从dsk_icos根据uid得到有应用的id
    				 * 下面进行用户配置的更新操作
    				 */
    				M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('screenlist'=>addslashes(serialize($screenlist_u))));
    				/*
    				 * 更新之后 查看数据库，更新正常
    				 */
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
    			for($i=1;$i<=$sysconfig['userscreennum'];$i++){
    				$navids_u[]="$i";
    				$screenlist_u[$i]['config']=array(
    						'navid'=>$i,
    						'navname'=>'桌面'.$i,
    						'type'=>'desktop',
    						'autolist'=>$sysconfig['autolist'],
    						'marginleft'=>$sysconfig['marginleft'],
    						'margintop'=>$sysconfig['margintop'],
    						'marginright'=>$sysconfig['marginright'],
    						'marginbottom'=>$sysconfig['marginbottom'],
    						'iconview'=>$sysconfig['iconview'],
    						'iconposition'=>$sysconfig['iconposition'],
    						'dockshow'=>$sysconfig['dockshow'],
    						'topbarshow'=>$sysconfig['topbarshow']
    				);
    				$screenlist_u[$i]['icos']=array();
    				$screenlist_u[$i]['wins']=array();
    				$screenlist_u[$i]['widgets']=array();
    					
    			}
    			
    			/*
    			 * 桌面初始化结束
    			 * 生成的$screenlist_u就是一个具有你设置的桌面数的数组
    			 * 里面的每一个的icos是空的
    			 */
    			
    			/*
    			 * docklist 这里应该是桌面下面的dock工具栏数组
    			 */
    			$docklist_u=array();
    			//这里原来是个乱码，具体干什么的，现在偶也不知道
    			$setupwids=array();
    			/*
    			 * 应该是从插件表里面调出要显示的插件，
    			 * 这里面现在是个空的 
    			 */
    			$result = M('dsk_widget')->where("uid='-1'")->findAll();
    			/*
    			 * $result = 空，但不是NUll啊，输出是个空
    			 * 下面的循环现在没有走
    			 */
    			foreach ($result as $value){
    				if(!$value['notdelete']){
    					unset($value['gid']);
    					$value['uid']=$this->mid;
    					$value['username']=getRealName($this->mid);
    					$value['gid']=M('dsk_widget')->add($value);
    				}
    				if($value['desktop']>0 && in_array($value['desktop'],$navids_u)){
    					$gidarr=array('gid'=>$value['gid'],
    							'navid'=>$value['desktop'],
    							'left'=>0,
    							'top'=>0,
    							'zIndex'=>100
    					);
    					$screenlist_u[$value['desktop']]['widgets'][$value['gid']]= $gidarr;
    				}
    				$setupwids[]=$value['oid'];
    			}
    			
    			/*
    			 * $result是空的
    			 * 这个循环也没有走
    			 */
    			//获取系统应用信息
    			$setupids=array();
    			foreach ($result as $value){
    				//如果该系统应用是可以删除的情况下
    				if(!$value['notdelete']){
    					unset($value['icoid']);
    					$value['uid']=$this->mid;
    					$value['username']=getRealName($this->mid);
    					$value['icoid']=M('dsk_icos')->add($value);
    				}
    				if($value['desktop']<0) $docklist_u[]=$value['icoid'];
    				if($value['desktop']>0) $screenlist_u[$value['desktop']]['icos'][]=$value['icoid'];
    				$setupids[]=$value['oid'];
    			}
    			
    			$setarr=array(
    					'uid'=>$this->mid,
    					'username'=>getRealName($this->mid),
    					'docklist'=>implode(',',$docklist_u?$docklist_u:$docklist_0),
    					'screenlist'=>serialize($screenlist_u),
    					'iconpositions'=>serialize(array()),
    					'dateline'=>time(),
    					'current'=>1
    			);    			
    			/*
    			 * 当登录成功的时候，但是ts_dsk_userconfig这个表里面没有uid对应信息的时候，下面就是把上面的初始化信息
    			 * 插入到ts_dsk_userconfig这个表里面
    			 * （这个地方 ，小伟那边也不插入，具体的没有调试）
    			 */
    			M('dsk_userconfig')->add($setarr);
    			/*
    			 * 更新各个应用的安装使用情况
    			 * 更新统计数据
    			 */
    			if($setupids) M()->query("update ".C('DB_PREFIX')."dsk_apps set setupnum=setupnum+1 where appid IN (".dimplode($setupids).")");
    			if($setupwids) M()->query("update ".C('DB_PREFIX')."dsk_widget_market set setupnum=setupnum+1 where appid IN (".dimplode($setupids).")");
    		}
    	}
    	
    	$allicoids_u=array();
    	/*
    	 * $screenlist_u这个是各个不同屏幕的配置信息，这个里面第一个屏幕里面的已经有了用户在dsk_icos里面的应用显示    	 * 
    	 */

    	foreach($screenlist_u as $key =>$desktop){
    		//这个应该是设置当前是哪个屏幕的
    		if($desktop['config']['isdefault']){
    			$data['currentDesktop']=$key;
    		} 
    		//遍历每个屏幕里面的icos这个数组，现在应该是只有第一个有数据,为了得到$allicoids_u这个变量
    		foreach($desktop['icos'] as $icoid =>$value){
    			$allicoids_u[]=$value;
    		}
    		//???这里面为什么要把wins里面的也放到icoid里面，这个wins是什么意思啊
    		foreach($desktop['wins'] as $icoid=>$value){
    			$allicoids_u[]=$value['icoid'];
    		}
    		foreach($desktop['widgets'] as $icoid=>$value){
    			$gids[]=$value['gid'];
    		}
    	}
    	/*
    	 * 上面 的操作应该是遍历了全部的屏幕，用来得到现在已经有的$allicoids_u和$gids
    	 */
    	/*
    	 * 这里面为什么要合并这两个数组，不知道
    	 */
    	$allicoids_u=array_merge($allicoids_u,$docklist_u);
    	/*
    	 * 如果用户已经登录，下面进行处理
    	 */
    	if($this->mid){
    		//获取系统默认应用(重构)
    		/*
    		 * 获取系统默认的应用，也就是dsk_icos里面uid = -1 and notdelte = 1的应用
    		 */
    		$result=M('dsk_icos')->where("uid='-1' and notdelete=1")->findAll();
    		/*
    		 * 下面的循环的作用是：
    		 * 先判断系统默认的应用在不在上面 得到的全部用户自定义的应用当中
    		 * 如果没有，在进行是桌面上的应用还是普通应用的
    		 * 	如果desktop大于0，就是在desktop上这个id的桌面上显示，如果是0，就不显示了
    		 * 	如果desktop == -1，这个是判断 什么的，不太懂
    		 * $allicoids_u这个icos全部的变量及$docklist_u变量里
    		 */
    		foreach ($result as $value){
    			if(!in_array($value['icoid'],$allicoids_u)){
    				if($value['desktop']>0 && in_array($value['desktop'],$navids_u)){
    					$screenlist_u[$value['desktop']]['icos'][]=$value['icoid'];
    					$allicoids_u[]=$value['icoid'];
    				}elseif($value['desktop']==-1){
    					$docklist_u[]=$value['icoid'];
    					$allicoids_u[]=$value['icoid'];
    				}
    			}
    		}
    		
    		/*
    		 * 原注释是个乱码，应该是得到系统插件操作，不过，这个地方是空的
    		 * 思路应该是和上面 一样的，只不是全部的变量是$gids
    		 */
    		$result=M('dsk_widget')->where("uid='-1' and notdelete>0")->findAll();
    		foreach ($result as $value){
    			if(!in_array($value['gid'],$gids)){
    				if($value['desktop']>0 && in_array($value['desktop'],$navids_u)){
    					$gidarr=array('gid'=>$value['gid'],
    							'navid'=>$value['desktop'],
    							'left'=>0,
    							'top'=>0,
    							'zIndex'=>100
    					);
    						
    					$screenlist_u[$value['desktop']]['widgets'][$value['gid']]=$gidarr;
    					$gids[]=$value['gid'];
    	
    				}
    			}
    		}
    	}
    	//print_r($allicoids_0);
    	//得到用户应用列表信息
    	$allicoids=array_merge($allicoids_u,$allicoids_0);
    	/*
    	 * RickerYu 添加的，过滤数组值为空的操作
    	 */
    	foreach ($allicoids as $key=>$val){
    		if(empty($val)){
    			unset($allicoids[$key]);
    		}
    	}
    	//print_r($allicoids);
    	/*
    	 * 下面这个是根据条件产生的ID生成图标所需的必要参数
    	 * 
    	 */
    	$icosdata=$icoids=$folderids=$appids=$videoids=$linkids=$imageids=$uids=$blogids=$musicids=$aids=array();
    	if($allicoids){
    		$result=M('dsk_icos')->where("icoid IN(".dimplode($allicoids).") OR (uid='{$this->mid}' and isshow='1') OR (uid='-1' and notdelete='1')")->findAll();
    		foreach ($result as $value){
    			if($value['type']=='folder') $folderids[]=$value['oid'];
    			$value['url']=replace_param($value['url']);
    			$value['fsize']=formatsize($value['size']);
    			$value['ftype']=getFileTypeName($value['type'],$value['ext']);
    			$value['fdateline']=dgmdate($value['dateline']);
    			if(!$value['ext']) $value['ext']=strtolower(substr(strrchr($value['url'], '.'), 1, 10));
    			$icosdata[$value['icoid']]=$value;
    				
    			if($value['isshow']){
    				$icoids[]=$value['icoid'];
    			}
    		}
    	}
    	//print_r($icoids);
    	//print_r($screenlist_u);
    	//exit();
    	
    	$folderdata=array();
    	if($folderids){
    		$result = M('dsk_folder')->where('fid IN ('.dimplode($folderids).')')->findAll();
    		$folderids=array();
    		foreach ($result as $value){
    			if($value['ids']) $value['ids']=explode(',',$value['ids']);
    			else $value['ids']=array();
    			$folderdata[$value['fid']]=$value;
    			$folderids[]=$value['fid'];
    		}
    	}
    	
    	/*
		*
		*/
    	foreach($screenlist_u as $key =>$desktop){
    		if(!in_array($key,$navids_u)) unset($screenlist_u[$key]);
    		foreach($desktop['icos'] as $icoid =>$value){
    			if(!in_array($value,$icoids)){
    				unset($screenlist_u[$key]['icos'][$icoid]);
    			}
    		}
    	}
    	foreach($screenlist_0 as $key =>$desktop){
    		if(!in_array($key,$navids_0)) unset($screenlist_0[$key]);
    		foreach($desktop['icos'] as $icoid =>$value){
    			if(!in_array($value,$icoids)){
    				unset($screenlist_0[$key]['icos'][$icoid]);
    			}
    		}
    	}
    	if(!$this->mid){
    		$docklist=array();
    		foreach($docklist_0 as $key =>$value){
    			if(in_array($value,$icoids)){
    				$docklist[]=$value;
    			}
    		}
    	}else{
    		$docklist=array();
    		foreach($docklist_u as $key =>$value){
    			if(in_array($value,$icoids)){
    				$docklist[]=$value;
    			}
    		}
    	}
    	//��ȡ���йҼ����?
    	$widgetdata=array();
    	$gids_all=array();
    	if($gids){
    		$result = M('dsk_widget')->where("gid IN(".dimplode($gids).")")->findAll();
    		foreach ($result as $value){
    			$gids_all[]=$value['gid'];
    			$value['url']=replace_param($value['url']);
    			$widgetdata[$value['gid']]=$value;
    		}
    	}
    	
    	
    	//�ж����û�д˹Ҽ������ ��ɾ�������ϵĴ˹Ҽ���gid
    	foreach($screenlist_u as $key =>$desktop){
    		foreach($desktop['widgets'] as $icoid =>$value){
    			if(!in_array($icoid,$gids_all)){
    				unset($screenlist_u[$key]['widgets'][$icoid]);
    			}
    		}
    	}
    	foreach($screenlist_0 as $key =>$desktop){
    		foreach($desktop['widgets'] as $icoid =>$value){
    			if(!in_array($icoid,$gids_all)){
    				unset($screenlist_0[$key]['widgets'][$icoid]);
    			}
    		}
    	}

    	$data['formhash']=$this->formhash;
    	
    	$data['navids']=array_merge($navids_0,$navids_u);
    	$data['docklist']=$docklist;
    	$data['screenlist']=array('screenlist_0'=>$screenlist_0,'screenlist_u'=>$screenlist_u);
    	$data['sourceids']=array(
    			'icos'=>$icoids,
    			'folder'=>$folderids,
    	);
    	$data['sourcedata']=array(
    			'icos'=>$icosdata ? $icosdata : array(),
    			'folder'=>$folderdata ? $folderdata : array(),
    			'plugin'=>$plugins,
    			'widgets'=>$widgetdata,
    	);
    	
    	$space = $this->space;		//获取当前用户的个人空间设置重要
    	$space['attachextensions']=$space['attachextensions'] ? explode(',',$space['attachextensions']) : array();
    	$data['space']=$space;
    	
    	$arr=array();
    	if($this->mid) $arr=M('dsk_thame')->where("id='{$config_u[thame]}'")->find();
    	if(!$arr){
    		$arr=M('dsk_thame')->where("`default`='1'")->find();
    	}
    	if(!$arr['backimg']) $arr['backimg']= __THEME__.'/desktop/styles/thame/'.$arr['folder'].'/back.jpg';		//桌面背景设置
    	//print_r($arr);
    	$data['thame']['system']=$arr;
    	$custom_backimg='';
    	
    	$data['thame']['custom']=array(
    			'custom_backimg'=>$config_u['custom_backimg']?$config_u['custom_backimg']:'',
    			'custom_url'=>$config_u['custom_url']?$config_u['custom_url']:'',
    			'custom_window'=>$config_u['custom_window']?$config_u['custom_window']:'',
    			'custom_browser'=>$config_u['custom_browser']?$config_u['custom_browser']:'',
    			'custom_topbar'=>$config_u['custom_topbar']?$config_u['custom_topbar']:'',
    			'custom_filemanage'=>$config_u['custom_filemanage']?$config_u['custom_filemanage']:'',
    			'custom_dock'=>$config_u['custom_dock']?$config_u['custom_dock']:'',
    			'custom_btype'=>$config_u['custom_btype']?$config_u['custom_btype']:''
    	);
    	
    	$data['thame']=dstripslashes($data['thame']);
    	if($config_u['current'] && (in_array($config_u['current'],$navids_0) || in_array($config_u['current'],$navids_u))) $data['currentDesktop']=$config_u['current'];
    	if(!$data['currentDesktop']) $data['currentDesktop']=$navids_0[0];
    	if($this->space['self']){
    		$ukey=random(16);
    		if(M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('ukey'=>$ukey))){
    			$data['ukey']=$ukey;
    		}else{
    			$data['ukey']=$config_u['ukey'];
    		}
    	}
    	
    	// print_r($data);
    	//print_r($space);

    	echo json_encode($data);
    	exit();

    }
	
    /**
     +----------------------------------------------------------
     * 个人主题设置的保存方法（_config.js JS调用�?
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间�?013-3-21 上午10:56:39
     +----------------------------------------------------------
     */
    public function save(){
		if(!$this->space['self']) exit();
		$arr=array('msg'=>'success');
		$ukey=trim($_GET['ukey']);
		$result = M('dsk_userconfig')->field('ukey')->where("uid='{$this->mid}'")->find();
		if($ukey != $result['ukey']){
			$arr['msg']=L('need_refresh');
		}else{		//当用户不是重复提交时
			if($_REQUEST['folderlist']){
			    $folders=unserialize(stripslashes(trim($_REQUEST['folderlist'])));
			    foreach($folders as $key =>$value){
			    	unset($value['dataline']);
			    	$fid=$value['fid'];
			    	unset($value['fid']);
			    	unset($value['position']);
			    	unset($value['opened']);
			    	$value['ids']=implode(',',$value['ids']);
			    	$value['pfid']=intval(trim($value['pfid']));
			    	$value['updatetime']=time();
			    	M('dsk_folder')->where("fid='{$fid}'")->save($value);
			    }
			}
			    	
		    $setarr=array();
		    $docklist = $_REQUEST['docklist'];
		    if(isset($docklist)) $setarr['docklist']=trim($docklist);
		    $screenlist = $_REQUEST['screenlist'];
		    if($screenlist){
		    	if(unserialize(stripslashes(trim($screenlist)))){
		    		$setarr['screenlist']=$screenlist;
		    	}
		    }
		    $iconpositions_u = $_REQUEST['iconpositions_u'];
		    if(isset($iconpositions_u) && unserialize(stripslashes(trim($iconpositions_u)))){
		    	$setarr['iconpositions']=stripslashes(trim($iconpositions_u));
		    }
		    if($_REQUEST['spacename']) $setarr['spacename']=trim($_REQUEST['spacename']);
		    if($_REQUEST['friend']!='') $setarr['friend']=intval($_REQUEST['friend']);
		    if($_REQUEST['current']){$setarr['current']=trim($_REQUEST['current']);}
		    if($_REQUEST['thame']){
		    	$setarr['thame']=trim($_REQUEST['thame']);
		    	$setarr['custom_backimg']=trim($_REQUEST['custom_backimg']);
		    	$setarr['custom_url']=trim($_REQUEST['custom_url']);
		    	$setarr['custom_window']=trim($_REQUEST['custom_window']);
		    	$setarr['custom_browser']=trim($_REQUEST['custom_browser']);
		    	$setarr['custom_topbar']=trim($_REQUEST['custom_topbar']);
		    	$setarr['custom_filemanage']=trim($_REQUEST['custom_filemanage']);
		    	$setarr['custom_dock']=trim($_REQUEST['custom_dock']);
		    	$setarr['custom_btype']=trim($_REQUEST['custom_btype']);
		    }
		    $setarr['updatetime']=time();
		    M('dsk_userconfig')->where("uid='{$this->mid}'")->save($setarr);
		    
		    $screenlist_0 = $_REQUEST['screenlist_0'];
		    if($this->space['self']>1 && $screenlist_0){
		    	if(unserialize(stripslashes(trim($screenlist_0)))){
		    		M('dsk_userconfig')->where("uid='0'")->save(array('screenlist'=>stripslashes(trim($screenlist_0))));
		    	}
		    }
		    $iconpositions_0 = $_REQUEST['iconpositions_0'];
		    if($this->space['self']>1 && isset($iconpositions_0) && unserialize(stripslashes(trim($iconpositions_0)))){
		    	M('dsk_userconfig')->where("uid='0'")->save(array('iconpositions'=>stripslashes(trim($iconpositions_0))));
		    }
		}
		
		echo json_encode($arr);
		exit();
    }
    
    /**
     +----------------------------------------------------------
     * 应用评分功能
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间�?013-4-12 下午2:36:14
     +----------------------------------------------------------
     */
    public function score(){
    	$id=intval($_GET['id']);
    	$star=intval($_GET['star']);
    	$idtype=trim($_GET['idtype']);
    	if($this->mid){
    		$scorearr=array(
    				'id'=>$id,
    				'star'=>$star,
    				'idtype'=>$idtype,
    				'uid'=>$this->mid,
    				'username'=>getUserName($this->mid),
    				'dateline'=>time()
    		);
    		$arr=array();
    		
    		if($arr=M('dsk_score')->where("uid='{$this->mid}' and idtype='{$idtype}' and  id='{$id}'")->find()){
    			M('dsk_score')->where("pid='{$arr[pid]}'")->save($scorearr);
    		}else{
    			M('dsk_score')->add($scorearr);
    		}
    		updatescore($scorearr['id'],$idtype);
    	}
    	
    	$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 删除应用功能
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-23 下午3:36:09
     +----------------------------------------------------------
     */
    public function deleteIco(){
    	$ukey=trim($_GET['ukey']);
    	if($ukey!=M('dsk_userconfig')->where("uid='{$this->mid}'")->getField('`ukey`')){
    		$arr=array();
    		$arr['msg']='refresh';
    		echo json_encode($arr);
    		exit();
    	}
    	
    	$arr=array();
    	$icoid=intval($_GET['icoid']);
    	$desktop=($_GET['desktop']);
    	$pfid=intval($_GET['pfid']);
    	if($this->space['self']){
    		if(($msg=dsk_delete_ico($icoid))=='success'){
    			//刷新文件夹
    			if($pfid){
    				if($oids=M('dsk_folder')->where("fid='{$pfid}'")->getField('ids')){
    					$ids=explode(',',$oids);
    					$flag=0;
    					foreach($ids as $key=>$value){
    						if($icoid==$value){
    							$flag=1;
    							unset($ids[$key]);
    						}
    					}
    					if($flag) M('dsk_folder')->where("fid='{$pfid}'")->save(array('ids'=>implode(',',$ids)));
    				}
    			}elseif($desktop){
    				if($config=M('dsk_userconfig')->where("uid='{$this->mid}'")->find()){
    					$screenlist=unserialize(stripslashes($config['screenlist']));
    					if($screenlist[$desktop]) {
    						$ids=$screenlist[$desktop]['icos'];
    						foreach($ids as $key=>$value){
    							if($icoid==$value){
    								$flag=1;
    								unset($ids[$key]);
    							}
    						}
    						$screenlist[$desktop]['icos']=array_values($ids);
    						M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('screenlist'=>addslashes(serialize($screenlist))));
    					}
    				}
    			}elseif($desktop==-1){
    				if($config=M('dsk_userconfig')->where("uid='{$this->mid}'")->find()){
    					$docklist=explode($config['docklist']);
    					foreach($docklist as $key=>$value){
    						if($icoid==$value){
    							$flag=1;
    							unset($docklist[$key]);
    						}
    					}
    					M('dsk_userconfig')->where("uid='{$this->mid}'")->save(array('docklist'=>implode(',',$docklist)));
    				}
    			}
    			$arr['msg']='success';
    		}else{
    			$arr['msg']=$msg;
    		}
    	}else{
    		$arr['msg']= L('no_privilege');
    	}
    	echo json_encode($arr);
    	exit();
    }
	
    /**
     +----------------------------------------------------------
     * 资源管理器
     +----------------------------------------------------------
     * @author 小波 2013-6-15
     +----------------------------------------------------------
     */
    public function explorer(){
    	$do = empty($_GET['do']) ? $_POST['do'] : $_GET['do'];
    	//判断是否是外部提交
    	if (!empty($do)) {
    		$this->$op();
    		exit();
    	}
    }
    
    /**
     +----------------------------------------------------------
     * 文件管理器
     +----------------------------------------------------------
     * @author 小波 2013-6-15
     +----------------------------------------------------------
     */
    private function filemanage(){

    	$winid=$_GET['winid'];
    	$sid=empty($_GET['id']) ? 0 : $_GET['id'];
    	$icoid=intval($_GET['icoid']);
    	$data=array();
    	$data1=array();
    	list($prex,$id,$fuid)=explode('-',$sid);
    	$folderdata=array();
    	$folderids=array();
    	if($prex=='f'){
    		$container='icosContainer_folder_'.$id;
    		$arr=array();
    		$icos=array();
    	
    		if($folder=M("dsk_folder")->where("fid='{$id}'")->find()){
    			$open_p=1;
    			if($folder['friend']==1){
    				if(!$this->space['self']) $open_p=0;
    			}elseif($folder['friend']==2){
    				if(!$this->space['self'] && !$this->space['isfriend']) $open_p=0;
    			}
    			if($open_p){
    				$folder['opened']=1;
    				if($folder['ids']){
    					$icos=explode(',',$folder['ids']);
    				}else{
    					$icos=array();
    				}
    				$folder['ids']=$icos;
    				if($icoid) $icos[]=$icoid;
    				if($icos){
    						
    					$query=M("dsk_icos")->where("icoid IN (".dimplode($icos).")")->findAll();
    					foreach($query as $value){
    						$value['fsize']=formatsize($value['size']);
    						$value['ftype']=getFileTypeName($value['type'],$value['ext']);
    						$value['fdateline']=date("Y-m-d", $value['dateline']);
    						$open_p=1;
    						if($value['friend']==1){
    							if(!$this->space['self']) $open_p=0;
    						}elseif($value['friend']==2){
    							if(!$this->space['self'] && !$this->space['isfriend']) $open_p=0;
    						}
    						if(!$open_p){
    							$value['img']=C('APP_PUBLIC_PATH').'/images/default_ys/'.$value['type'].'.png';
    						}
    						$arr[$value['icoid']]=$value;
    						if($value['type']=='folder') $folderids[]=$value['oid'];
    					}
    					//������Ŀ¼��˳������
    					foreach($icos as $value){
    						if($value!=$icoid)	$data[$value]=$arr[$value];
    						else $data1=$arr[$icoid];
    					}
    				}
    				$folderdata[$folder['fid']]=$folder;
    	
    				//Ŀ¼���
    				if($folderids){
    					$query=M("dsk_folder")->where("fid IN (".dimplode($folderids).")")->findAll();
    					foreach($query as $value){
    						if($value['ids']) $value['ids']=explode(',',$value['ids']);
    						else $value['ids']=array();
    						$folderdata[$value['fid']]=$value;
    					}
    				}
    			}
    			$json_folder=json_encode($folderdata);
    			$json_data1=json_encode($data1);
    		}
    	}elseif($prex=='d'){
    		if($config_u=M("dsk_userconfig")->where("uid='$fuid'")->findAll()){
    			//获取用户桌面上的图标
    			$screenlist_u=unserialize(stripslashes($config_u['screenlist']));
    			$icos=$screenlist_u[$id]['icos'];
    		}
    		$container='icosContainer_body_'.$id;
    		$folderids=array();
    		if($icos){
    			$query=M("dsk_icos")->where("icoid IN (".dimplode($icos).")")->findAll();
    			foreach($query as $value){
    				$value['fsize']=formatsize($value['size']);
    				$value['ftype']=getFileTypeName($value['type'],$value['ext']);
    				$value['fdateline']=date("Y-m-d", $value['dateline']);
    				$open_p=1;
    				if($value['friend']==1){
    					if(!$this->space['self']) $open_p=0;
    				}elseif($value['friend']==2){
    					if(!$this->space['self'] && !$this->space['isfriend']) $open_p=0;
    				}
    				if(!$open_p){
    					$value['img']=C('APP_PUBLIC_PATH').'/images/default_ys/'.$value['type'].'.png';
    				}
    				$arr[$value['icoid']]=$value;
    	
    				if($value['type']=='folder') $folderids[]=$value['oid'];
    			}
    		}
    		//��������˳������
    		foreach($icos as $icoid){
    			$data[$icoid]=$arr[$icoid];
    		}
    		//Ŀ¼���
    		$folderdata=array();
    		if($folderids){
    			$query=M("dsk_folder")->where("fid IN (".dimplode($folderids).")")->findAll();
    			foreach($query as $value){
    				if($value['ids']) $value['ids']=explode(',',$value['ids']);
    				else $value['ids']=array();
    				$folderdata[$value['fid']]=$value;
    			}
    		}
    		$json_folder=json_encode($folderdata);
    	}elseif($prex=='type'){
    		$container='icosContainer_type_'.$id;
    	
    	}elseif($prex=='friend'){
    		$container='icosContainer_frind_'.$id;
    	
    	}elseif($prex=='alluser'){
    		$container='icosContainer_alluser_'.$id;
    	}
    	
    	$json=json_encode($data);
    	
    	$this->display("filemanage");
    }
}
?>