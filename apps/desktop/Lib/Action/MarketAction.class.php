<?php
/**
 +------------------------------------------------------------------------------
 * 应用市场模块Action
 +------------------------------------------------------------------------------
 * @category	desktop
 * @package		Lib/Action
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-22 下午17:55:54
 +------------------------------------------------------------------------------
 */
class MarketAction extends BaseAction{
	protected $myapps = array();
	protected $myappgroups = array();
	private $userAppLimits;
	
	public function _initialize() {
		global $ts;
		parent::_initialize();
				
		//获取已安装的所有应用
		$data['install_app'] = array();
		$appsid = array();
		foreach ($ts['install_apps'] as $value){
			$appsid[] = $value['app_id'];
		}
		unset($map);
		$map['oid'] = array('in',$appsid);
		$apps = M('dsk_apps')->where($map)->findAll();
		//var_dump($apps);
		foreach ($apps as $app){
			$myapps[$app['appid']] = $app;
		}
		$this->myapps = $myapps;
		
		//获取用户应用分组查看权限
		$usergroups = $this->getUserGroupIds();
		if (!in_array('1',$usergroups)) {		//如果普通用户没有查看我的空间权限，则加入该权限
			$usergroups[] = '1';
		}
		if (!empty($usergroups)) {
			$where = " or (group_id in (".implode(',',$usergroups)."))";
		}
		//获取应用分组
		$this->myappgroups= (empty($this->myappgroups)) ? 
											model('AppGoup')->field('group_id,group_name,role_private')->where('group_id=1'.$where)->order('display_order ASC')->findAll() : 
											$this->myappgroups;
		$_GET['mod'] = strtolower(MODULE_NAME);
		$this->assign($_GET);
		$userAppLimit=$ts['userAppLimit'];
		$this->userAppLimits=array();
		foreach($userAppLimit as $value){
			if($value['appid'])
				$this->userAppLimits[]=$value['appid'];
		}
	}

	/**
	 +----------------------------------------------------------
	 * 应用市场页面首页
	 +----------------------------------------------------------
	 * @author	小朱 2013-6-24
	 +----------------------------------------------------------
	 * 创建时间：	2013-6-24 下午05:35:32
	 +----------------------------------------------------------
	 */
	public function index(){
		//系统应用分组
		$data['app_group'] = $this->myappgroups;
		
		//从数据中心获取是否有权限使用这些应用 2013年6月24日 小朱修改
		$map['appid'] =array('in',$this->userAppLimits);
		
		//该用户可以安装使用多少应用程序
		$data['all_app_count'] = M('dsk_apps')->where($where)->count();
		
		
		$data['install_app_order'] = M('user_app')
										->field('*,count(user_app_id) AS listorder')
										->group('app_id')
										->order('listorder DESC')
										->findPage(5);
		$this->assign($data);
		$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * ajax相关操作方法
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-11 下午1:58:20
     +----------------------------------------------------------
     */
    public function ajax(){
    	$_GET['do'] || $_GET['do'] = $_POST['do'];
    	if (!empty($_GET['do'])) {
    		$this->$_GET['do']();
    	}
    }
    
    /**
     +----------------------------------------------------------
     * 安装应用功能
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-17 上午9:19:16
     +----------------------------------------------------------
     */
    private function addapp(){
    	$arr=array();
    	$container=$_REQUEST['container'];
    	$appid=$_REQUEST['appid'];
    	
    	if(!$this->space['self'] ||(strpos($container,'icosContainer_body_sys_')!==false && $this->space['self']<2)){
    		$arr['msg']=L('no_privilege');
    		echo json_encode($arr);
    		exit();
    	}
    	//获取当前应用的具体信息
    	if($app=getapp($appid)){
    		$icoarr=array(
    				'uid'=>$this->mid,
    				'username'=>getUserName($this->mid),
    				'oid'=>$app['appid'],
    				'name'=>$app['appname'],
    				'url'=>$app['appurl'],
    				'img'=>$app['appico'],
    				'wwidth'=> empty($app['width']) ? 1034 : $app['width'],
    				'wheight'=> empty($app['height']) ? 600 : $app['height'],
    				'open'=> empty($app['open']) ? 0 : $app['open'],
    				'haveflash'=> empty($app['haveflash']) ? 0 : $app['haveflash'],
    				'idtype'=>empty($app['idtype']) ? 'app' : $app['idtype'],
    				'typeid'=> empty($app['typeid']) ? 0 : $app['typeid'],
    				'havetask'=> empty($app['havetask']) ? 1 : $app['havetask'],
    				'isshow'=> empty($app['isshow']) ? 1 : $app['isshow'],
    				'titlebuttons'=>empty($app['titlebuttons']) ? 'max,min,close' : $app['titlebuttons'],$app['titlebuttons'],
    				'type'=>'app',
    				'size'=>0,
    				'ext'=>'',
    				'friend'=>0,
    				'dateline'=>time(),
    				'app_type'=>$app['app_type']
    					
    		);
    		//先安装原系统应用流程
    		$app = model('App')->getAppDetailById($app['oid']);
    		if (!app){
    			$arr['msg']=L('app_notexist');
    			echo json_encode($arr);
    			exit();
    		}
    		if (model('App')->addAppForUser($this->mid, $app['app_id'])) {
    			model('App')->unsetUserInstalledApp($this->mid);
    		}
    		//将当前应用安装完成后的信息保存至桌面系统相关数据库
    		if($icoarr['icoid']=M('dsk_icos')->add($icoarr)){
    			$icoarr['container']=$container;
    			$icoarr['msg']='do_success';
    				
    			if(addtoconfig($icoarr['icoid'],$container)){
    				$icoarr['url']=replace_param($icoarr['url']);
    				$icoarr['fsize']=formatsize($icoarr['size']);
    				$icoarr['ftype']=getFileTypeName($icoarr['type'],$icoarr['ext']);
    				$icoarr['fdateline']=date($icoarr['dateline'],'Y-m-d H:i:s');
    				echo json_encode($icoarr);
    				M()->query("update ".$this->prefix."dsk_apps set hot=hot+3,setupnum=setupnum+1 where appid='{$appid}'");
    				if($this->mid)	M('dsk_userdo')->add(array('type'=>'setup','appid'=>$appid,'uid'=>$this->mid,'username'=>getUserName($this->mid),'dateline'=>time()));
    			}else{
    				dsk_delete_ico($icoarr['icoid']);
    				$arr['msg']='target error!';
    				echo json_encode($arr);
    			}
    			exit();
    		}else{
    			$arr['msg']='unkown_error';
    			echo json_encode($arr);
    			exit();
    		}
    	}else{
    		$arr['msg']='app not exist';
    		echo json_encode($arr);
    		exit();
    	}
    	
    }
    
    /**
     +----------------------------------------------------------
     * 更新点击统计
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-15 下午4:06:46
     +----------------------------------------------------------
     */
    private function updateview(){
    	$appid=intval($_GET['appid']);
    	if($_G['coockie']['view_app_'.$appid]){
    		exit();
    	}else{
    		M()->query("update ".$this->prefix."dsk_apps set hot=hot+1,viewnum=viewnum+1 where appid='{$appid}'");
    		dsetcookie('view_app_'.$appid,'1',86400);
    	}
    	$this->display('updateview');
    }
    
    private function main_region_rm(){
    	$this->display('main_region_rm');
    }
    
    /**
     +----------------------------------------------------------
     * 评论推荐应用幻灯列表
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-11 下午3:18:34
     +----------------------------------------------------------
     */
    private function main_region_lt(){
    	$list=array();
    	/* $query=DB::query("select r.*,a.appname,a.classid from ".DB::table('dzz_recomment')." r
						LEFT JOIN ".DB::table('dzz_apps')." a ON r.appid=a.appid
						where r.available>0 and r.type=0 order by r.disp");
    	while($value=dstripslashes(DB::fetch($query))){
    		$list[]=$value;
    	} */
    	$this->display('main_region_lt');
    }
    
    /**
     +----------------------------------------------------------
     * 最新上线应用
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-11 下午3:19:54
     +----------------------------------------------------------
     */
    private function main_region_lm(){
    	//获取我已经安装的应用
    	$data['myicos'] = getmyappid();
    	$list=array();
    	$appids = array();
    	foreach ($data['myicos'] as $key=>$value){
    		$appids[] = $key;
    		if(count($appids)>10)
    			break;
    	}
    	$where = "D.ismarket=0";
    	//查询未安装的最新应用
    	if(count($appids)){
    		$where .= " AND D.appid not IN (" . implode(',', $appids) . ")";
    	}
		
		//从数据中心获取是否有权限使用这些应用 2013年6月24日 小朱修改
		$where .=" and D.appid IN (" .implode(',',$this->userAppLimits) . ")";
		
    	$apps = M('')->table( $this->prefix . 'dsk_apps D' )
					->where( $where )
					->order( 'D.appid DESC' )
					->limit( '10' )
					->findAll();
    	foreach ($apps as $value){
    		//$value['setupnum'] = get_app_install_count($value['app_id'],$value['status']);
    		$list[$value['appid']]=$value;
    	}
    	
    	$data['list'] = $list;
    	$data['maxwidth']=count($list)*90;
    	$this->assign($data);
    	$this->display('main_region_lm');
    }
    
    private function main_region_lm2(){
    	$this->display('main_region_lm2');
    }
    
    /**
     +----------------------------------------------------------
     * 推荐应用页面右侧排行
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-26 下午1:35:00
     +----------------------------------------------------------
     */
    private function main_region_rank(){
    	$data['myicos'] = getmyappid();
    	$list=array();
    	$page=empty($_GET['page'])?1:intval($_GET['page']);
    	$perpage = 8;
    	$start = ($page-1)*$perpage;
    	
    	//获取有权限查看的应用
    	$appgroup = $this->myappgroups;
    	foreach ($appgroup as $group){
			$usergroups[] = $group['group_id'];
		}
		//获取全部应用
		$groupstr = array();
		if (is_array($usergroups)) {
			foreach ($usergroups as $group){
				$groupstr[] = "FIND_IN_SET('".$group."',`group_id`)";
			}
		}else{
			$groupstr[] = "FIND_IN_SET('".intval($usergroups)."',`group_id`)";
		}
		//获取已评分的全部数据条数
		$scores = M("dsk_score")->field("id,SUM(star)")->group("id")->findAll();
		$data['list'] = M()->table($this->prefix . "dsk_apps A")
									->join("INNER JOIN (SELECT S.id,SUM(S.star) as star,count(S.uid) as starnum FROM {$this->prefix}dsk_score S GROUP BY S.id ORDER BY star DESC) as C ON A.appid = C.id")
									->where("A.ismarket=0")
									->limit("{$start},{$perpage}")
									->findAll();
    	foreach ($data['list'] as &$app){
    		$core = compute_core($app['star'], $app['starnum']);
    		$app['star'] = $core['star'];
    		$app['corestyle'] = $core['corestyle'];
    	}

    	$total=ceil(count($scores)/$perpage);
    	$data['page'] = $page;
    	$data['total'] = $total;
    	$this->assign($data);
    	$this->display('main_region_rank');
    }
    
    /**
     +----------------------------------------------------------
     * 获取评论信息
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-15 下午4:50:25
     +----------------------------------------------------------
     */
    private function comment(){
    	$appid=intval($_GET['appid']);
    	$app=getapp($appid);
    	$this->assign('app',$app);
    	$this->display('comment');
    }
    
    /**
     +----------------------------------------------------------
     * 获取应用详细信息页面（左侧主要内容）
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-12 上午9:28:50
     +----------------------------------------------------------
     */
    private function introduce_region_lt(){
    	$appid=intval($_GET['appid']);
    	//获取当前用户所安装的app列表
    	$data['myicos'] = getmyappid();
    	
    	//获取当前用户的应用分组
    	foreach ($this->myappgroups as $value){
    		$data['appgroup'][$value['group_id']]=$value;
    	}
    	
    	//获取本APP的详细信息
    	$app=array();
    	$app=getapp($appid);
    	if(!$app) $this->error(L('app_not_exist'));
    	$icos = M('dsk_icos')->where("oid = '{$app['app_id']}'")->find();
    	//读取桌面系统中的应用安装表信息（以下ID在应用市场中的打开方法中使用）
    	$app['icoid'] = $icos['icoid'];
    	$data['app'] = $app;
    	
    	//评价评分相关信息
    	$data['score'] = get_core($appid);

    	//应用截图
    	$images=array();
    	$imageids=array();

    	$pics = M('dsk_pic')->where("id='{$appid}'")->order('viewnum DESC')->findAll();
    	foreach ($pics as $value){
    		$value['pic']=pic_get($value['filepath'], '', $value['thumb'], $value['remote'], 1,1);
    		$value['filepath']=pic_get($value['filepath'], '', $value['thumb'], $value['remote'], 0,1);
    		$data['images'][]=$value;
    		$data['imageids'][]=$value['picid'];
    	}
    	$data['imagesum']=count($images);
    	
    	$this->assign($data);
    	$this->display('introduce_region_lt');
    }
    
    /**
     +----------------------------------------------------------
     * 同类应用
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-15 下午5:38:26
     +----------------------------------------------------------
     */
    private function introduce_region_rt(){
    	$list=array();
    	$cid=intval($_GET['cid']);
    	$appid=intval($_GET['appid']);
    	
    	//当一个应用有多个分组的时候执行
    	$app = getapp($appid);
    	$groups = explode( ',' , $app['group_id'] );
    	$groupstr = array();
    	if (is_array($groups)) {
    		foreach ($groups as $group){
    			$groupstr[] = "FIND_IN_SET('".$group."',`group_id`)";
    		}
    	}else{
    		$groupstr[] = "FIND_IN_SET('".intval($cid)."',`group_id`)";
    	}

    	$data['list'] = model('app')
    						->where("`status`<>0 AND ( ".implode( ' OR ' , $groupstr )." ) AND app_id <> {$appid}")
    						->order('display_order ASC')
    						->limit(8)
    						->findAll();
    	
    	foreach ($data['list'] as &$app){
    		$app['setupnum'] = get_app_install_count( $app['app_id'] , $app['status'] );
    	}
    	
    	$this->assign($data);
    	$this->display('introduce_region_rt');
    }
    
    /**
     +----------------------------------------------------------
     * 大家喜欢的
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-15 下午6:06:56
     +----------------------------------------------------------
     */
    private function introduce_region_rb(){
    	$appids=array();
    	$userapps = M('user_app')->group('app_id')->order('ctime DESC')->limit('30')->findAll();
    	foreach ($userapps as $value){
    		$appids[$value['app_id']]=$value['app_id'];
    	}
    	if($appids){
    		$data=array();
    		$data['list'] = M('dsk_apps')->where("oid IN (".dimplode($appids).")")->findAll();
    		$data['count']=count($data['list']);
    	}
    	
    	$this->assign($data);
    	$this->display('introduce_region_rb');
    }
    
    /**
     +----------------------------------------------------------
     * 获取全部应用列表信息或者根据分组获取应用列表信息
     +----------------------------------------------------------
     * @param	$_GET['cid']分组id
     * @author	小朱 2013-6-24
     +----------------------------------------------------------
     * 创建时间：	2013-6-24 下午05:36:23
     +----------------------------------------------------------
     */
    private function appPanel_all_list_body(){
    	$data = $_GET;
    	//已安装应用
    	global $ts;
    	$data['myicos'] = getmyappid();
    	//var_dump($ts['install_apps']);
    	//var_dump($data['myicos']);
    	//exit;
    	
    	//获取与我相关应用分组
    	$gids = getuser_appgroup();
    	if (is_array($gids)) {
			foreach ($gids as $gid){
				$groupstr[] = "FIND_IN_SET('".$gid."',`group_id`)";
			}
		}else{
			$groupstr[] = "FIND_IN_SET('".intval($gids)."',`group_id`)";
		}

    	$page = empty($_GET['page'])?1:intval($_GET['page']);
    	
    	$cid=intval($_GET['cid']);
    	$sid=intval($_GET['sid']);
    	//排序
    	$sort=empty($_GET['sort'])?intval($_GET['sort']):0;
    	
    	//每页12个
    	$perpage = 12;
    	$start = ($page-1)*$perpage;
    	$gets = array(
    			'do'=>'appPanel_all_list_body',
    			'cid' => $cid,
    			'sid'=>$sid,
    			'sort'=>$sort,
    	);
    	$theurl = U('desktop/Market/ajax')."&".url_implode($gets);
    	
    	if($cid>0) $wheresql.="FIND_IN_SET('".intval($cid)."',`classids`)  and ";
    	
    	if($sort==0) 
    		$ordersql="`setupnum` DESC";	// 默认，最热
    	elseif($sort==1) 	//最新应用
    		$ordersql="`dateline` DESC";
    	elseif($sort==2) 
    		$ordersql="`star` DESC";
		
		//检查是否有权限
    	$wheresql .="appid IN (" .implode(',',$this->userAppLimits) . ")";
		
    	//格式化分页效果
		$data['data']['data'] = M('dsk_apps')->where($wheresql)->order($ordersql)->limit("{$start},{$perpage}")->findAll();
    	$data['data']['count'] = count($data['data']['data']);
    	foreach ($data['data']['data'] as &$value){
    		//评价评分相关信息
    		$value['score'] = get_core($value['app_id']);
    	}
    	$data['data']['realpages'] = Intval(@ceil($count / $perpage));
    	$data['data']['html'] = pageList($count, $perpage, $page, $theurl,0,6);
    	$data['data']['page'] = $page;
    	
    	
    	//如果为其它应用时，方法暂时未用
    	if (!$cid) {
    		$data['data'] = M('dsk_apps')->where('classid=0')->findPage($perpage);
    	}
    	
    	$this->assign($data);
    	//var_dump($data);
    	$this->display('appPanel_all_list_body');
    }
    
    /**
     +----------------------------------------------------------
     * 应用列表中展示的今日主打
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-25 上午9:35:26
     +----------------------------------------------------------
     */
    private function jinqianzhuang(){
    	$list=array();
    	$cid = intval($_GET['cid']);
    	$wheresql = "ismarket=0";
    	if($cid>0) $wheresql.=" and FIND_IN_SET('".intval($cid)."'classids)";
    	
    	$data['list'] = M()->table($this->prefix . 'user_app U')
    	->join("right join {$this->prefix}dsk_apps A ON U.app_id = A.oid")
    	->field('A.*,U.ctime as installtime')
    	->where($wheresql)
    	->group("A.oid")
    	->order('U.ctime DESC')
    	->limit('8')
    	->findAll();   	

    	$this->assign($data);
    	$this->display('jinqianzhuang');
    }
    
    /**
     +----------------------------------------------------------
     * 应用列表右侧排行(暂未启用)
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-25 上午10:34:55
     +----------------------------------------------------------
     */
    private function all_region_rb(){
    	$list=array();
    	$result = M()->table($this->prefix . 'app A')
    	->join($this->prefix . 'dsk_score S ON A.app_id = S.id')
    	->field('A.*,S.pid,COUNT(S.pid) as starnum,SUM(S.star) as star,S.dateline')
    	->order('S.dateline desc')
    	->where("idtype='appid'")
    	->limit('10')
    	->findAll();
    	 
    	foreach ($result as &$value){
    		//计算相关信息
    		$score = compute_core($value['star'], $value['starnum']);
    		$value = array_merge($value, $score);
    	}
    	$data['list'] = $result;
    	
    	$this->assign($data);
    	$this->display('all_region_rb');
    }
    /**
     +----------------------------------------------------------
     * 搜索功能自动提示
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-25 下午3:10:10
     +----------------------------------------------------------
     */
    private function autosearch(){
    	$keyword=urldecode(trim($_GET['keyword']));
    	$wheresql="status>0 and (app_alias LIKE '%".$keyword."%' or app_name LIKE '%".$keyword."%')";
    	$list=array();
    	$query=M("app")->field("app_alias")->where($wheresql)->order("LOCATE('{$keyword}',app_alias)")->limit(10)->findAll();
    	foreach ($query as $value){
    		$value['sappname']= str_replace($keyword, '<span class="height_light1">'.$keyword.'</span>', $value['app_alias']);
    		$list[]=$value;
    	}
    	$this->assign('list',$list);
    	$this->display('autosearch');
    }
    /**
     +----------------------------------------------------------
     * 应用搜索功能（搜索的同时，dsk_search更新关键词并做统计处理，查询出热门应用）
     +----------------------------------------------------------
     * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return	return_type <返回类型(void的方法就不用该选项)>
     * @author	小朱 2013-6-24
     +----------------------------------------------------------
     * 创建时间：	2013-6-24 下午06:45:17
     +----------------------------------------------------------
     */
    private function updatesearch(){
    	$keyword=urldecode(trim($_GET['keyword']));
    	if(M("app")->where("status>0 and app_alias LIKE '%".$keyword."%'")->count()){
    		if($srid=M("dsk_search")->field('srid')->where("keyword='{$keyword}'")->limit(1)->find()){
    			M()->query("update {$this->prefix}dsk_search set sum=sum+1 ,dateline=".time()." where srid='{$srid}'");
    		}else{
    			M('dsk_search')->add(array('keyword'=>$keyword,'dateline'=>time(),'sum'=>1));
    		}
    	}
    }
    
    /**
     +----------------------------------------------------------
     * 获取搜索的结果集
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-23 下午5:17:14
     +----------------------------------------------------------
     */
    private function search_region_l(){
    	$data = $_GET;
    	$keyword=urldecode(trim($_GET['keyword']));
    	$data['myapps'] = $this->myapps;
    	//获取与我相关应用分组
    	$gids = getuser_appgroup();
    	if (is_array($gids)) {
    		foreach ($gids as $gid){
    			$groupstr[] = "FIND_IN_SET('".$gid."',`group_id`)";
    		}
    	}else{
    		$groupstr[] = "FIND_IN_SET('".intval($gids)."',`group_id`)";
    	}
    	//查询我有权限看的应用分组信息
    	$query = M('app_group')
    	->where(" ( ".implode( ' OR ' , $groupstr )." )")
    	->order('display_order ASC')
    	->findAll();
    	foreach ($query as $value){
    		$data['appclass'][$value['group_id']][]=$value;
    	}
    
    	$page = empty($_GET['page'])?1:intval($_GET['page']);
    	$cid=intval($_GET['cid']);
    	$sid=intval($_GET['sid']);
    	$sort=intval($_GET['sort']);
    	 
    	$perpage = 12;
    	$start = ($page-1)*$perpage;
    	$gets = array(
    			'do'=>'appPanel_all_list_body',
    			'cid' => $cid,
    			'sid'=>$sid,
    			'sort'=>$sort,
    	);
    	$theurl = U('desktop/Market/ajax')."&".url_implode($gets);
    	 
    	$wheresql="status>0 and ( ".implode( ' OR ' , $groupstr )." )";
    	if($cid>0) $wheresql.=" and FIND_IN_SET('".intval($cid)."',`group_id`)";
    	if($sid){
    		$appids=array();
    		$query=M('dsk_relative')->field('appid')->where("classid='{$sid}'")->findAll();
    		foreach ($query as $value){
    			$appids[]=$value['appid'];
    		}
    		if($appids)	$wheresql.=" and app_id IN (".dimplode($appids).")";
    		else $wheresql.=" and app_id IN ('0')";
    	}
    	if (!empty($keyword)) {
    		$wheresql.=" and app_alias LIKE '%".$keyword."%'";
    	}
    	if($sort==0) $ordersql="`status` ASC";
    	elseif($sort==1) $ordersql="`display_order` ASC";
    	elseif($sort==2) $ordersql="`star` DESC";
    	else $ordersql="display_order";
    	$list=array();
    	 
    	$count = M('app')->where($wheresql)->order($ordersql)->count();
    
    	$data['data']['data'] = M('app')->where($wheresql)->order($ordersql)->limit("{$start},{$perpage}")->findAll();
    	$data['data']['count'] = count($data['data']['data']);
    	foreach ($data['data']['data'] as &$value){
    		$value['setupnum'] = get_app_install_count($value['app_id'],$value['status']);
    		//评价评分相关信息
    		$value['score'] = get_core($value['app_id']);
    		//搜索结果标题高亮显示
    		$value['sappname']= str_replace($keyword, '<span class="height_light1">'.$keyword.'</span>', $value['app_alias']);
    	}
    	$data['data']['realpages'] = Intval(@ceil($count / $perpage));
    	//重新格式化分页展示样式
    	$data['data']['html'] = pageList($count, $perpage, $page, $theurl,0,6);
    	$data['data']['page'] = $page;
    	 
    	$this->assign($data);
    	$this->display('search_region_l');
    }
    /**
     +----------------------------------------------------------
     * 大家正在搜
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-26 上午10:56:58
     +----------------------------------------------------------
     */
    private function search_region_rt(){
    	$data['list']=M('dsk_search')->order("dateline DESC")->limit(10)->findAll();
 
    	$this->assign($data);
    	$this->display('search_region_rt');
    }
    /**
     +----------------------------------------------------------
     * 热搜关键词
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-4-26 上午11:06:09
     +----------------------------------------------------------
     */
    private function search_region_rb(){
    	$data['list']=M('dsk_search')->order("sum DESC")->limit(10)->findAll();
    	
    	$this->assign($data);
    	$this->display('search_region_rb');
    }

}
?>