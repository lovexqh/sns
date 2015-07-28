<?php
//相册应用 - indexaction 照片和专辑的列表
class IndexAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
        //惟恐时提示
        $temp_empty="<li class='item masonry-brick' style='padding: 10px;'>没有数据了</li>";
        $temp_empty_xc="<li  style='padding: 10px;'>暂时没有数据</li>";
        $this->assign('temp_empty_xc',$temp_empty_xc);
        $this->assign('temp_empty',$temp_empty);
	}

	/**
	 * 相册的首页显示
	 * 包括最新相册及热门相册
	 */
	public function index(){
		$type = isset($_GET['type']) ? $_GET['type'] : 'new';

		$title = '最新照片';
		$photo = D('Photo')->getListPhoto(1);
		if($type == 'hot'){
			$order = ' readCount DESC ';
			$title = '热门照片';
			$photo = D('Photo')->getListPhoto(1,$order);
		}
		$this->right($type);
		$this->setTitle($title.' - 我的相册 ');
		$this->assign('title',$title);
		$this->assign('photo',$photo);
		$this->assign('type',$type);
		$this->display();
    }

	public function right($type = 'new'){

		$rphoto = D('Photo')->getList(' readCount DESC',2);
		if($type == 'hot'){
			$rphoto = D('Photo')->getList(' cTime DESC',2);
		}

		$hotuser = D('Photo')->getHotUser();
		$this->assign('hotuser',$hotuser);
		$this->assign('rphoto',$rphoto);
	}

	/**
	 * 所有照片专辑列表信息
	 */
	public function all_albums(){

		$order = NULL;
		$map   = '';
        $_GET['order']=isset($_GET['order'])?$_GET['order']:"new";
		switch($_GET['order']) {
			case 'hot':    //热门排行
                $order = 'readCount DESC';
                $map  .=' photoCount>0 ';
                $map  .=' AND (privacy=1 or privacy=4  ) ';
                $this->setTitle('热门' . $this->appName);
				break;
			case 'following':    //关注的人的相册
				$order='mTime DESC';
				$in_arr = M('weibo_follow')->field('fid')->where("uid={$this->mid} AND type=0")->findAll();
				$in_arr = $this->_getInArr($in_arr);
                $map['photoCount'] = array('gt',0);
                $map['userId'] = array('in',$in_arr);
				$map['privacy'] = array('in','1,2,4');

				$this->setTitle('我关注的人的' . $this->appName);
				break;
			default:      //默认最新排行
                $order = 'mTime DESC';
                $map  .=' photoCount>0 AND (privacy=1 or privacy=4) ';
                $this->setTitle('最新' . $this->appName);


		}
		//获取相册数据
		$data	=	D('Album')->order($order)->where($map)->findPage(15);
		$this->assign('data',$data);
        $this->assign('order',$_GET['order']);
		$this->setTitle('所有专辑 - 我的相册 ');
		$this->display('all_albums');
	}

	public function search(){
		$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
		if(empty($keyword)){

		}else{
			$photolist = D('Photo')->getSearchPhoto($keyword);
		}
		$this->assign('keyword',$keyword);
		$this->assign('photo',$photolist);
        $this->setTitle('照片搜索页面 ');
		$this->display();
	}

	//显示一张照片
	public function photo() {
		$id   = intval($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$photo = D('Photo')->getPhotoInfo($id);
		if($photo ==  false){
			$this->assign('jumpUrl', U('photo/Index/index'));
			$this->error('照片不存在或已被删除！');
			exit();
		}

		//获取所在相册信息
		$albumDao = D('Album');
		$album = $albumDao->getAlbumInfo($photo['albumId']);

		if(!$album){
			$this->assign('jumpUrl', U('photo/Index/index'));
			$this->error('专辑不存在或已被删除！');
		}

		$num = D('Photo')->getPhotoNum($photo['userId']);
		$selfuid = ($photo['userId'] ==  $this->mid);
		$this->assign('num',$num);
		$this->assign('selfuid',$selfuid);
		$this->assign('photo',$photo);

		//隐私控制
		if($this->mid!=$album['userId']){
			$relationship	=	getFollowState($this->mid,$album['userId']);
			if($album['privacy']==3){
				$this->error('这个'.$this->appName.'的照片，只有主人自己可见。');
			}elseif($album['privacy']==2 && $relationship=='unfollow'){
				$this->error('这个'.$this->appName.'的照片，只有主人关注的人可见。');
			}elseif($album['privacy']==4){;
				$cookie_password	=	$_COOKIE['album_password_'.$album['id']];
				//如果密码不正确，则需要输入密码
				if($cookie_password != md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'].'_'.$this->mid)){
					$this->need_password($album,$id);
					exit;
				}
			}
		}

		$followstate = D('Follow','weibo')->getState($this->mid, $album['userId'], 0);
		$this->assign('followstate',$followstate);
		$this->assign('album',$album);

		//获取所有照片数据
		$photos	=	$albumDao->getPhotos($album['id']);
		if(!$photos){
			$this->error('照片列表数据错误！');
			exit();
		}
		$pre['_string'] = ' id >  '.$id.' AND albumId = '.$album['id'].' ';
		$data['pre'] = D('Photo','photo')->getProNext($pre,'id ASC');
		$next['_string'] = ' id <  '.$id.' AND albumId = '.$album['id'].' ';
		$data['next'] = D('Photo','photo')->getProNext($next,'id DESC');

		//点击率加1
		D('Photo')->upPhotoNum($id);
		$this->assign('photos',$photos);
		$this->setTitle(getUserName($album['userId']).'的照片：'.$photo['name']);
		/**
		 * 右边显示的操作方法
		 */
		$this->user_right($album['userId']);
        $this->assign('uid',$album['userId']);
		$this->assign($data);
		$this->display();
	}

	//显示一个照片专辑
	public function album() {

		$id		=	intval($_REQUEST['id']);
		//获取相册信息
		$albumDao = D('Album');
		$album	  =	$albumDao->where("id={$id}")->find();

		if(!$album){
			$this->assign('jumpUrl', U('photo/Index/index'));
			$this->error('专辑不存在或已被删除！');
		}
		$followstate = D('Follow','weibo')->getState($this->mid, $album['userId'], 0);
		$this->assign('followstate',$followstate);

		$num = D('Photo')->getPhotoNum($album['userId']);
		$this->assign('num',$num);

		//隐私控制
		if($this->mid!=$album['userId']){
            $relationship	=	getFollowState($this->mid, $album['userId']);

			if($album['privacy']==3){
				$this->error('这个'.$this->appName.'，只有主人自己可见。');
			}elseif($album['privacy']==2 && $relationship=='unfollow'){
				$this->error('这个'.$this->appName.'，只有关注自己的人可见。');
			}elseif($album['privacy']==4){;
				$cookie_password	=	$_COOKIE['album_password_'.$album['id']];
				//如果密码不正确，则需要输入密码
				if($cookie_password != md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'].'_'.$this->mid)){
					$this->need_password($album);
					exit;
				}
			}
		}
		$this->user_right($album['userId']);

		//获取所有照片数据
		$photos	=	$albumDao->getPhotos($album['id'],'id DESC',20);
		if(!$photos){
			$this->error('照片列表数据错误！');
			exit();
		}
		$this->assign('photos',$photos);
		//点击率加1
		$albumDao->execute('UPDATE '.C('DB_PREFIX').$albumDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND userId={$this->uid} LIMIT 1");

        $this->assign('uid',$album['userId']);
		$this->setTitle($album['name']);
		$this->assign('selfuid',($this->mid == $album['userId']));
		$this->assign('album',$album);
		$this->display();
	}

	private function user_right($uid){
        $map['privacy'] = array('in','1,4');
		$relationship	=	getFollowState($this->mid,$uid);

        if($this->mid==$uid){
            unset($map['privacy']);
        }elseif($relationship !='unfollow'){
			$map['privacy'] = array('in','1,2,4');
		}

		$map['userId'] = $uid;
		$albums = D('Album')->getMapAlbums($map,'id,userId,name');
		$this->assign('right_albums',$albums);
	}

	function _getInArr($in_arr) {
		$in_str = array();
		foreach($in_arr as $key=>$v) {
			$in_str[] = $v['fid'];
		}
		return $in_str;
	}

	//某人的全部照片
	public function photos() {
		$uid = isset($_GET['uid']) ? $_GET['uid'] : '';
		if(empty($uid)){
			$uid = $this->mid;
		}
		//隐私控制
		if($this->mid!=$this->uid){
			$relationship	=	getFollowState($this->mid,$uid);
			if($relationship=='eachfollow'||$relationship=='havefollow'){
				$map['privacy']	= array('in',array(1,2,4));
			}else{
				$map['privacy']	= array('in',array(1,4));
			}
		}
		$followstate = D('Follow','weibo')->getState($this->mid, $uid, 0);
		$this->assign('followstate',$followstate);

		$num = D('Photo')->getPhotoNum($uid);
		$this->assign('num',$num);

		//获取配置参数
		$config = getConfig();
		//获取照片数据
		$order	=	' `mTime` DESC';
		$map['userId']	=	$uid;
		$this->user_right($uid);
		$photos	=	D('Photo')->order($order)->where($map)->findPage($config['photo_raws']);
		$data['p'] = isset($_GET['p']) ? $_GET['p'] : 1;

        //防止多次加载
        if($photos['totalPages']+1<$photos['nowPage']){
            exit;
        }

		$this->assign('type','mAll');
		$this->assign('uid',$uid);
		$this->assign('isself',($this->mid == $uid));
		$this->assign('photos',$photos);
		$this->assign($data);
		$this->display();
	}

	//某人的全部专辑
	public function albums() {
		//获取相册数据
		$map['userId']	=	$this->uid;
		$map['isDel']	=	0;

		$data	=	D('Album')->order("mTime DESC")->where($map)->findPage(getConfig('album_raws'));

		//获取微广播相册
		$weibo  =  D('WeiboAttach','weibo')->getWeiboAlbum($this->uid);

		$this->assign('weibo',$weibo);
		$this->assign('data',$data);

		$this->display();
	}

	//输入相册密码
	public function need_password($album,$pid='') {

//		$aid	=	intval($_REQUEST['aid']);
//		$pid	=	intval($_REQUEST['pid']);
//		$uid	=	intval($_REQUEST['uid']);
//
//		//获取相册信息
//		$album	=	D('Album')->where(" id='$aid' AND userId='$uid' ")->find();
//
//		if(!$album){
//			$this->error('专辑不存在或已被删除！');
//		}

		$this->assign('username',getUserName($album['userId']));
        $this->assign('uid',$album['userId']);
		$this->assign('pid',$pid);
		$this->assign('album',$album);
		$this->display('need_password');
	}

	//验证相册密码
	public function check_password() {

		$aid	=	intval($_REQUEST['aid']);
		$uid	=	intval($_REQUEST['uid']);
		$password	=	t($_REQUEST['password']);
		$_REQUEST['pid'] && $pid = intval($_REQUEST['pid']);
		//获取相册信息
		$album	=	D('Album')->where(" id='$aid' AND userId='$uid' ")->find();
		$id = $album['id'];
		if($album['isDel'] != 0){
			$this->error('专辑不存在或已被删除！');
		}
		if($password == $album['privacy_data']){
		// 	//跳转到照片页面
		// 	$url	=	U('/Index/photo',array('uid'=>$album['userId'],'aid'=>$album['id']));
		// }else{
			//跳转到相册页面
			$url	=	U('/Index/album',array('uid'=>$album['userId'],'id'=>$album['id']));
		}
		//验证密码
		if( $password == $album['privacy_data'] ){

			//加密保存密码
			$cookie_password	=	md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'].'_'.$this->mid);
			//密码保存7天
            setcookie('album_password_'.$album['id'] , $cookie_password , time()+3600*24*7 );

			$this->assign('jumpUrl',$url);
			$this->success('密码验证成功，将自动保存7天。马上跳转到'.$this->appName.'页面！');

		}else{
			$this->assign('jumpUrl',$url);
			$this->error('密码验证失败！');
		}
	}

	public function weiboalbum(){

		//微广播相册 ID = 0
		if( $id==0 && $this->uid > 0 ){
			$weibo  =  D('WeiboAttach','weibo')->getWeiboAlbum($this->uid);
		}
		//获取微广播照片数据
		$config = getConfig();

		// $photos = D('WeiboAttach','weibo')->getUserAttachData($this->uid,1,$config['photo_raws']);
		$photos = D('WeiboAttach','weibo')->getUserAttachDataNew($this->uid,1,$config['photo_raws']);

		$this->assign('photos',$photos);
		$this->setTitle(getUserName($this->uid).'的微广播相册');

		$this->assign('album',$weibo);
		$this->display();
	}


	//显示一张照片
	public function weibophoto() {

		$id		=	intval($_REQUEST['id']);
		$uid	=	intval($_REQUEST['uid']);

		//获取所有照片数据
		$photos = D('WeiboAttach','weibo')->getUserAttachData($this->uid,1);
		$photos = $photos['data'];

		//验证照片信息是否正确
		if(!$photos){
			$this->error('照片不存在或已被删除！');
		}

		//获取当前照片信息
		foreach($photos as $v){
			if($v['id']==$id){
				$photo	=	$v;
			}
		}

		//获取照片微广播信息
		$weibo	=	D('Weibo','weibo')->getOne($photo['weibo_id']);
		$_REQUEST['p'] = $_GET['p'];
		$comment=	D('Comment','weibo')->getComment($photo['weibo_id']);
		$privacy=	D('UserPrivacy','home')->getPrivacy($this->mid,$photo['userId']);

		$this->assign('weibo',$weibo);
		$this->assign('comment',$comment);
		$this->assign('privacy',$privacy);
		$this->assign('photo',$photo);
		$this->assign('photos',$photos);

		//获取上一页 下一页 和 预览图
		if($photos){
			foreach($photos as $v){
				$photoIds[]	=	intval($v['id']);
			}

			$photoCount	=	count($photoIds);

			//颠倒数组，取索引
			$pindex		=	array_flip($photoIds);

			//当前位置索引
			$now_index	=	$pindex[$id];

			//上一张
			$pre_index	=	$now_index-1;
			if( $now_index <= 0 )	{
				$pre_index	=	$photoCount-1;
			}
			$pre_photo	=	$photos[$pre_index];

			//下一张
			$next_index	=	$now_index+1;
			if( $now_index >= $photoCount-1 ) {
				$next_index	=	0;
			}
			$next_photo	=	$photos[$next_index];

			//预览图的位置索引
			$start_index	=	$now_index - 2;
			if($photoCount-$start_index<5){
				$start_index	=	($photoCount-5);
			}
			if($start_index<0){
				$start_index	=	0;
			}

			//取出预览图列表 最多5个
			$preview_photos	=	array_slice($photos,$start_index,5);
		}else{
			$this->error('照片列表数据错误！');
		}
		//点击率加1
		//$photoDao->execute('UPDATE '.C('DB_PREFIX').$photoDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND albumId={$aid} AND userId={$this->uid} LIMIT 1");

		$this->assign('photoCount',$photoCount);
		$this->assign('now',$now_index+1);
		$this->assign('pre',$pre_photo);
		$this->assign('next',$next_photo);
		$this->assign('previews',$preview_photos);

		unset($pindex,$photos,$album,$preview_photos);

		$this->setTitle(getUserName($this->uid).'的微广播照片：'.$photo['name']);

		$this->display();
	}

	/**
	 *
	 * @Title: topsearch
	 * @Description: 大桌面模糊查询操作函数
	 * @return 显示一个搜索列表页面
	 * @author RickerYu rickeryu@gridinfo.com.cn
	 */
	
	public function topsearch(){
		$data['search_key'] = $_GET['searchtxt'];
		$data['sty'] = $_GET['sty'];
		$pagesize = 15;
		$name['status'] = 1;
		if(!empty($data['search_key'])){
			$name['name']  = array('like','%'.$data['search_key'].'%');
				
			switch ($data['sty']){
				case 'photo':
					/*
					 * 查找社团文档的基本信息
						*/
					$data['result'] = M('photo')->where($name)->findpage($pagesize);
					$data['tag'] = '照片';
					break;
				default:
					/*
					 * 查找社团的基本信息
						*/
					$pagesize = 10;
					$data['result'] = M('photo_album')->where($name)->findpage($pagesize);
					$data['tag'] = '相册';
					break;
			}
		}
		//print_r($data);
		$this->assign($data);
		$this->display();
	}
	
	//大家的照片
	public function favorite() {
		$order = 'cTime DESC';
		$this->setTitle('我收藏的照片');
		
		$photos	=	model('Favorite')->list_favorite_all('photo','20','photo');

		$this->assign('photos',$photos);
		$this->display('all_photos');
	}
}
?>