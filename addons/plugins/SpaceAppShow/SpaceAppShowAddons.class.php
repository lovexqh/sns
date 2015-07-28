<?php
class SpaceAppShowAddons extends SimpleAddons
{
	protected $version = '1.0';
	protected $author  = '网格插件';
	protected $site    = 'www.gridinfo.com.cn';
	protected $info    = '个人主页应用展示';
	protected $pluginName = '个人主页应用展示';
	protected $tsVersion  = "2.5";                               // ts核心版本号


	private static $validApps = array(
	            'blog'=>'文章',
	            'vote'=>'投票',
	            'group'=>'社团',
	            'photo'=>'相册',
				'video' =>'视频',
	        );

    /**
     * getHooksInfo
     * 获得该插件使用了哪些钩子聚合类，哪些钩子是需要进行排序的
     * @access public
     * @return void
     */
    public function getHooksInfo(){
		$this->apply("home_space_tab", "home_space_tab");
		$this->apply("home_space_list", "home_space_list");
		$this->apply("home_space_middle", "home_space_middle");
		//$this->apply("home_space_middle_new", "home_space_middle_new");
    }

	/* 文章、投票、社团 */
	public function home_space_tab($param)
	{
	    $config= model('AddonData')->lget('space_app_show');
		$uid  = $param['uid'];
		$menu = & $param['menu'];
		$apps = array(
			'blog'  => '文章',
			'vote'  => '投票',
			'group' => '社团',
			'photo' => '相册',
			'video' =>'视频',
		);
        foreach ($apps as $key => $value) {
	        if (model('App')->isAppExistForUser($uid, $key) && in_array($key,$config['open'])) {
        		$menu[$key] = $value;
	        }
	        unset($apps[$key]);
        }
	}

	public function home_space_list($param)
	{
	    $config= model('AddonData')->lget('space_app_show');
		$uid = $param['uid'];
		$app = $param['type'];
		if(!in_array($app,$config['open'])) return;
		$function_name = '_' . $app;
		if (method_exists($this, $function_name)) {
			$this->$function_name($uid);
		}
	}

	private function _blog($uid)
	{
		// 文章列表
		$field = '*';
        $map            = array();
        if ($uid != $this->mid) {
        	$map['private'] = array('eq',0);
        }
        $map['status']  = 1;
        $map['uid']     = $uid;
		$data = M('blog')->field( $field )->where( $map )->order('id DESC')->findPage(20);
		$this->assign($data);
		$this->display('blog');
	}

	private function _vote($uid)
	{
		// 投票列表
		$field = '*';
        $map['uid']= $uid;
		$data = M('vote')->field($field)->where($map)->order('id DESC')->findPage(20) ;//选项
        $optDao = M('vote_opt');
        foreach($data['data'] as $k=>$v) {
            $opts = $optDao->where("vote_id = {$v['id']}")->order("id asc")->field("*")->limit( '0,2' )->findAll();
            $data['data'][$k]['opts'] = $opts;
        }
		$this->assign($data);
		$this->display('vote');
	}

	private function _group($uid)
	{
		require_once SITE_PATH . '/apps/group/Common/common.php';
        $isLogin = empty($_SESSION['mid']) ? false : true;
        $this->assign('isLogin', $isLogin);
		$this->assign($data);
		$this->display('group');
	}
	
	private function _photo($uid){
		$uid = $_GET['uid'];
		 
		$menu = array ('photo'=>'相册');
		$this->assign('space_menu', $menu);
		//获取相册数据
		$map['userId']	=	$uid;
		$map['isDel']	=	0;

        $map['privacy'] = array('in','1,4');
        $relationship	=	getFollowState($this->mid,$uid);

        if($this->mid==$uid){
            unset($map['privacy']);
        }elseif($relationship !='unfollow'){
            $map['privacy'] = array('in','1,2,4');
        }
		
		$data	=	M('photo_album')->order("mTime DESC")->where($map)->findPage($this->getConfig_photo('album_raws'));
		//获取微广播相册
		$weibo  =  D('WeiboAttach','weibo')->getWeiboAlbum($this->uid);
		
		$this->assign('weibo',$weibo);
		$this->assign('data',$data);
		
		$this->display('albums');
	}
	private function  _video($uid){
		
		 
		$menu = array ('video'=>'视频');
		$this->assign('space_menu', $menu);
		
		//获取配置参数
		$config = $this->getConfig_video();
		//获取视频数据
		$order	=	'categoryId DESC,cTime DESC';
		$map['userId']	=	$uid;
		$map['isDel']	=	0;
		$map = service('ForeAdmin')->getAuditStatus($map, 0, 'video');
		$videos	=M('video')->order($order)->where($map)->findPage($config['video_raws']);
 		$this->assign('video_preview',$config['video_preview']);
		$this->assign('type','mAll');
		$this->assign('videos',$videos);
		$this->display('video');
		
		
		
	}
	
	
	/* 相册 
	public function home_space_middle($param)
	{
	    $config= model('AddonData')->lget('space_app_show');

		$uid = $param['uid'];
		if (model('App')->isAppExistForUser($uid, 'photo')  && in_array('photo',$config['open'])) {
    		if ($uid == $this->mid) {
    		} else if ('unfollow' == getFollowState($uid, $this->mid)) {
                $photo_map['privacy'] = 1;
    		} else {
    			$photo_map['privacy'] = array('IN', '1,2');
    		}
    		$photo_map['userId'] = $uid;
    		$data['photo_list'] = D('Photo', 'photo')->where($photo_map)->order('id DESC')->limit(4)->findAll();
    		$data['photo_preview'] = model('Xdata')->get('photo:photo_preview');
        }
		if ($data['photo_list']) {
			$this->assign($data);
			$this->display('photo');
		}
	}*/
	
	
	//新相册
	public function home_space_middle($param)
	{
		$config= model('AddonData')->lget('space_app_show');
		$uid = $param['uid'];
		$data['uid'] = $uid;
		$data['isowen'] = $this->mid == $uid ? 1 : 0;
		if (model('App')->isAppExistForUser($uid, 'photo')  && in_array('photo',$config['open'])) {
			if ($uid == $this->mid) {
			} else if ('unfollow' == getFollowState($uid, $this->mid)) {
				$photo_map['privacy'] = 1;
			} else {
				$photo_map['privacy'] = array('IN', '1,2');
			}
			$photo_map['userId'] = $uid;
			$data['photo_list'] = D('Photo', 'photo')->where($photo_map)->order('id DESC')->limit(9)->findAll();
			$photo_num = count($data['photo_list']);
			$data['photo_preview'] = model('Xdata')->get('photo:photo_preview');
		}
		$this->assign('photo_num',$photo_num);
		$this->assign($data);
		$this->display('newphoto');
	}

	public function install(){
	    $data['open']=array('photo','blog','group','vote');
	    return model('AddonData')->lput('space_app_show', $data)?true:false;
	}

	/* 后台管理 */
	public function adminMenu()
	{
	    return array('config' => '全局配置');
	}
	public function config(){
	    $config= model('AddonData')->lget('space_app_show');
	    $this->assign('valid',self::$validApps);
	    $this->assign('config',$config);
	    $this->display('config');
	}

	public function saveConfig(){
	    if(empty($_POST)) 
	    	$this->error('最少开启一个应用类型');
	    if(empty($_POST['open'])) $_POST['open'] = array();
	    $data = $_POST;
	    $res = model('AddonData')->lput('space_app_show', $data);
	    if ($res) {
	        $this->assign('jumpUrl', Addons::adminPage('config'));
	        $this->success();
	    } else {
	        $this->error();
	    }
	    exit;
	}
	//获取应用配置参数
	function getConfig_photo($key=NULL){
		$config = model('Xdata')->lget('photo');
		$config['album_raws'] || $config['album_raws']=6;
		$config['photo_raws'] || $config['photo_raws']=8;
		$config['photo_preview']==0 || $config['photo_preview']=1;
		($config['photo_max_size']=floatval($config['photo_max_size'])*1024*1024) || $config['photo_max_size']=-1;
		$config['photo_file_ext'] || $config['photo_file_ext']='jpeg,gif,jpg,png';
		$config['max_flash_upload_num'] || $config['max_flash_upload_num']=10;
 
		$config['open_watermark']==0 || $config['open_watermark']=1;
		$config['watermark_file'] || $config['watermark_file']='public/images/watermark.png';
		if($key==NULL){
			return $config;
		}else{
			return $config[$key];
		}
	}
	
	function getConfig_video($key = NULL) {
		$config = model('Xdata')->lget('video');
		$config['category_raws'] || $config['category_raws'] = 6;
		$config['video_raws'] || $config['video_raws'] = 8;
		$config['video_preview'] == 0 || $config['video_preview'] = 1;
		($config['video_max_size'] = floatval($config['video_max_size']) * 1024 * 1024) || $config['video_max_size'] = -1;
		$config['video_file_ext'] || $config['video_file_ext'] = 'mp4,flv,wmv,avi';
		$config['max_flash_upload_num'] || $config['max_flash_upload_num'] = 10;
		$config['open_watermark'] == 0 || $config['open_watermark'] = 1;
		$config['watermark_file'] || $config['watermark_file'] = 'public/images/watermark.png';
		if ($key == NULL) {
			return $config;
		} else {
			return $config[$key];
		}
	}

}