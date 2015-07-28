<?php
/**
 +------------------------------------------------------------------------------
 * ESN 图片操作类Action
 +------------------------------------------------------------------------------
 * @category	ESN UploadAction
 * @package		ESN Lib/Action
 * @author		小朱 <changqizhu@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 上午09:20:00
 +------------------------------------------------------------------------------
 */
class UploadAction extends Action
{   
private $db_prefix;
    private $classid;//班级编号id
	private $classinfo;//array类型，从api和sns中查询到的班级基础信息
	private $identityid;
	private $identitytype;
	private $action;//方法名
	private $module;//控制器名称
	private $adviser;
	/**
	 +----------------------------------------------------------
	 * _initialize 初始化加载，验证classid是否有值，并且验证是否是管理员
	 +----------------------------------------------------------
	 * @param	int uid 用户id
	 * @param	int classid 班级id
	 * @param	array classinfo 班级基础信息
	 * @param	int adviser	1有权限0没有权限
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 上午11:01:51
	 +----------------------------------------------------------
	 */
function _initialize(){
	   $this->action=ACTION_NAME;//获取当前访问方法名
	   $this->module=MODULE_NAME;//获取当前访问控制器名
	   $this->db_prefix = C('DB_PREFIX');
	   $this->uid=$this->mid;//获取当前登录uid
	   $uc_session=arrayKeyTolower($_SESSION['ucInfo']);//当前
	   $this->identityid=$uc_session['identityid'];
	   $this->identitytype=$uc_session['identitytype'];
	   if(empty($_REQUEST['classid']) || !intval($_REQUEST['classid'])>0){
			$this->error('非法操作！');
		}
	   $this->classid=intval($_REQUEST['classid']);
	   $this->assign('classid',$this->classid);
	   $uid=$this->mid;
	   $this->assign($uid);
	   D('Visit')->_visited($this->uid,$this->classid);//记录访问记录
	   $this->classinfo=D('ClassBasic')->_classInfo($this->classid);//获取班级基础信息
	   $this->assign('classinfo',$this->classinfo);
	   if($this->_getclassAdviser()){
	   		$this->adviser=1;
	   }else if($this->_ishavePower()){
	   		$powerlist=M('')->table($this->db_prefix.'class_manager as m,'.$this->db_prefix.'class_setmanager as s')
							->where("m.id=s.mid and s.classid=".$this->classid." and s.identityid=".$this->identityid)
							->order("m.id ASC")
							->findall();
	   		$this->adviser=2;
	   		$this->assign('powerlist',$powerlist);
	   }else{
	   		$this->adviser=0;
	   }
	   $this->assign('adviser',$this->adviser);
	  
    }

	/**
	 +----------------------------------------------------------
	 * 单张图片上传
	 +----------------------------------------------------------
	 * @param	int albumId 相册id
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:21:34
	 +----------------------------------------------------------
	 */
    public function upload_single_pic(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$info['status']=false;
				echo json_encode($info);exit;
	  		}
		}else if($this->adviser==0){
				$info['status']=false;
				echo json_encode($info);exit;
		}
		$albumId	=	intval($_REQUEST['albumId']);
		$classid	=	$this->classid;
		$albumDao   =   D('ClassAlbum');
		$albumInfo	=	$albumDao->field('id')->find($albumId);
		if(!$albumInfo)echo "0";
		$config     =   getConfig();
		$options['userId']		=	$this->mid;
		$options['classid']		=	$classid;
		$options['allow_exts']	=	$config['photo_file_ext'];
		$options['max_size']    =   $config['photo_max_size'];

		$info	=	X('Xattach')->upload('classphoto',$options);
		if($info['status']){
			//缩图规格
			$size['small']['x']	=	120;
			$size['small']['y']	=	120;
			$size['middle']['x']	=	465;
			$size['middle']['y']	=	-1; //不限制

			//缩图路径-文件名
			$bigpic		=	$info['info'][0]['savepath'].$info['info'][0]['savename'];
			$smallpic	=	$info['info'][0]['savepath'].'small_'.$info['info'][0]['savename'];
			$middlepic	=	$info['info'][0]['savepath'].'middle_'.$info['info'][0]['savename'];

			//缩图
			include_once SITE_PATH.'/addons/libs/Image.class.php';
			Image::thumb( UPLOAD_PATH.'/'.$bigpic , UPLOAD_PATH.'/'.$smallpic , '' , $size['small']['x'] , $size['small']['y'] );
			Image::thumb( UPLOAD_PATH.'/'.$bigpic , UPLOAD_PATH.'/'.$middlepic , '' , $size['middle']['x'] , ($size['middle']['y']==-1)?'auto':$size['middle']['y'] );
			//保存图片信息
			$info['info'] = $this->_save_photo($albumId,$info['info']);
			//重置相册图片数
			$albumDao->updateAlbumPhotoCount($albumId);
			//上传成功
			echo json_encode($info);
		}else{
			//上传出错
			echo json_encode($info);
		}
    }
	
	/**
	 +----------------------------------------------------------
	 * 创建相册
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:22:34
	 +----------------------------------------------------------
	 */
	public function create_album_tab(){
		$this->display();	
	}
	
	/**
	 +----------------------------------------------------------
	 * 图片上传后编辑操作
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:22:34
	 +----------------------------------------------------------
	 */
	public function muti_edit_photos() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$upnum		=	intval($_REQUEST['upnum']);
		$classid		=	$this->classid;
		if($upnum==0)
			$this->error('请至少上传一张图片！');
		$albumId	=	intval($_REQUEST['albumId']);
		$albumDao   =   D('ClassAlbum');
		$albumInfo	=	$albumDao->find($albumId);

		if(!$albumInfo){
			$this->error('请上传到指定的相册！');
		}
		if( $upnum >0) {
			$classphotos=	D('ClassPhoto')->limit($upnum)->order("id DESC")->where("classid=".$classid)->findAll();
			
			$this->assign('photos',$classphotos);
			$this->assign('album',$albumInfo);
			$this->assign('upnum',$upnum);

			$albumlist	=	$albumDao->where("classid=".$classid)->findAll();
			$this->assign('albumlist',$albumlist);

			$this->display();

		}else{

			$this->error('上传出错：没有上传任何图片！');
		}
	}
	//保存上传的图片
	public function save_upload_photos() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		//相册信息
		$albumId		=	intval($_POST['albumId']);
		$album_cover	=	intval($_POST['album_cover']);
		$upnum			=	intval($_POST['upnum']);
		$classid			=	$this->classid;
		$albumDao       =   D('ClassAlbum');
		$albumInfo		=	$albumDao->find($albumId);
		if(!$albumInfo){
			$this->error('请先正确选择相册，再上传图片！');
		}
		/*处理图片信息*/
		$photoDao       =   D('ClassPhoto');

		//解析图片数据
		foreach($_POST['name'] as $k=>$v){
			$new_photos[$k]['name']		=	$v;
			$new_photoids[]	=	$k;
		}
		foreach($_POST['move_to'] as $k=>$v){
			$new_photos[$k]['albumId']	=	$v;
		}
		//对比原始数据，筛选出需要更新的图片
		$photo_ids['id']	=	array('in',$new_photoids);
		$old_photos			=	$photoDao ->where($photo_ids)->findAll();
		foreach($old_photos as $k=>$v){
			//如果相册ID和名称都没变化，不需要保存
			$photoid	=	$v['id'];
			if($v['albumId']==$new_photos[$photoid]['albumId'] && $v['name']==$new_photos[$photoid]['name'] ){
				unset($new_photos[$photoid]);
			}
		}

		//保存图片信息并统计新图片数
		foreach($new_photos as $k=>$v){
			unset($map);
			$map['classid']		=	$this->classid;
			$map['albumId']		=	$v['albumId'];
			$map['name']		=	$v['name'];
			//相册信息更新
			$photoDao->limit(1)->where("id='$k'")->save($map);
			//重置相册图片数
			$albumDao->updateAlbumPhotoCount($map['albumId']);
		}

		/*   处理相册信息  */
        //重置相册图片数
	    $albumDao->updateAlbumPhotoCount($albumId);
		//如果相册封
		if($album_cover){
			$album['coverImageId']	=	$album_cover;
			if($coverInfo	=	$photoDao->field('id,savepath')->find($album_cover)){
				$album['coverImagePath']=	$coverInfo['savepath'];
				$albumDao->where("id='$albumId'")->save($album);
			}
		}

		
		if(intval($_POST['publish_weibo'])==1){
			$newphotoCount = count($new_photoids);
			$photo_ids['albumId'] = $albumId;
			$photoInfo = $photoDao->where($photo_ids)->order('id ASC')->find();
			if(!$photoInfo)$photoInfo = $photoDao->where(array('id'=>array('in',$new_photoids)))->order('id ASC')->find();
			$_SESSION['publish_weibo']=urlencode(serialize(array('count'=>$newphotoCount,'author'=>getUserName($this->mid),'title'=>$photoInfo['name'],'url'=>U('space/Upload/album',array('id'=>$photoInfo['id'],'classid'=>$photoInfo['classid'],'aid'=>$photoInfo['albumId'])),'type'=>1,'type_data'=>$photoInfo['savepath'])));
		}
		//dump($_SESSION['publish_weibo']);
		//exit;
		//跳转到相册页面
		//$this->redirect('/Index/album/id/'.$albumId.'/uid/'.$this->mid);
		$this->assign('jumpUrl',U('space/Upload/album',array(id=>$albumId,classid=>$this->classid)));
		$this->success('图片上传保存成功！');
	}
	
	/**
	 +----------------------------------------------------------
	 * 显示一个图片专辑
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:23:10
	 +----------------------------------------------------------
	 */
	public function album() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$id		=	intval($_REQUEST['id']);
		//获取相册信息
		$albumDao = D('ClassAlbum');
		$album	  =	$albumDao->where("id={$id}")->find();

		if(!$album){
			$this->assign('jumpUrl', U('space/Manage/ManagePhoto',array(classid=>$this->classid)));
			$this->error('专辑不存在或已被删除！');
		}
		//获取图片数据
		//$raws	=	$this->setting['photo_raws'];
		//$order	=	$this->setting['album_default_order'];
		$order	=	'`order` DESC,id DESC';
		$map['albumId']	=	$id;
		$map['classid']	=	$this->classid;
		$map['isDel']	=	0;

		$config = getConfig();
		$photos	= D('ClassPhoto')->order($order)->where($map)->findPage($config['photo_raws']);
		if($photos['data']){
			$this->assign('photos',$photos);

			//点击率加1
			$albumDao->execute('UPDATE '.C('DB_PREFIX').$albumDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND userId={$this->uid} LIMIT 1");
			$this->assign('photo_preview',$config['photo_preview']);
			$this->assign('album',$album);
		}
		$this->setTitle($album['name']);
		$this->display();
	}
	
	
  
	
	/**
	 +----------------------------------------------------------
	 * 保存照片信息
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:23:32
	 +----------------------------------------------------------
	 */
	public function _save_photo($albumId,$attachInfos) {
		//保存图片附件进入相册 并进行积分操作
		foreach($attachInfos as $k=>$v){
			$photo['attachId']	=	$v['id'];
			$photo['albumId']	=	$albumId;
			$photo['classid']	=	$v['classid'];
			$photo['cTime']		=	time();
			$photo['mTime']		=	time();
			$photo['name']		=	substr($v['name'],'0',strpos($v['name'],'.'));	//去掉后缀名
			$photo['size']		=	$v['size'];
			$photo['savepath']	=	$v['savepath'].$v['savename'];
			$photo['order']		=	10000;
			$photo['classid']		=	$this->classid;

			$photoid            =   D('ClassPhoto')->add($photo);
			//dump($this->getLastSql());
			$attachInfos[$k]['photoId']		=	$photoid;
			$attachInfos[$k]['albumId']		=	$albumId;
		}

		return $attachInfos;
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 显示一张照片信息
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:24:16
	 +----------------------------------------------------------
	 */
	public function photo() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$classid  = $this->classid;
		$aid  =	intval($_REQUEST['aid']);
		$id   = intval($_REQUEST['id']);
		$type =	t($_REQUEST['type']);	//图片来源类型，来自某相册，还是其它的

		//判断来源类型
		if(!empty($type) && $type!='mAll'){
			$this->error('错误的链接！');
		}
		$this->assign('type',$type);

		//获取所在相册信息
		$albumDao = D('ClassAlbum');
		$album = $albumDao->find($aid);
		if(!$album){
			$this->assign('jumpUrl', U('space/Manage/ManagePhoto',array(classid=>$this->classid)));
			$this->error('专辑不存在或已被删除！');
		}

		//获取图片信息
		$photoDao = D('ClassPhoto');
		$photo	  =	$photoDao->where(" albumId={$aid} AND `id`={$id} AND classid={$classid} ")->find();
		$this->assign('photo',$photo);

		//验证图片信息是否正确
		if(!$photo){
			$this->assign('jumpUrl', U('space/Upload/album', array('classid'=>$classid,'id'=>$aid)));
			$this->error('图片不存在或已被删除！');
		}

		$this->assign('album',$album);
		//$order	=	$this->setting['album_default_order'];

		//获取所有图片数据
		$photos	=	$albumDao->getPhotos($this->classid,$aid,$type,$order,5);
		//$this->assign('photos',$photos);

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
			$this->error('图片列表数据错误！');
		}
		//点击率加1
		$photoDao->execute('UPDATE '.C('DB_PREFIX').$photoDao->tableName." SET readCount=readCount+1 WHERE id={$id} AND albumId={$aid} AND classid={$classid} LIMIT 1");

		$this->assign('photoCount',$photoCount);
		$this->assign('now',$now_index+1);
		$this->assign('pre',$pre_photo);
		$this->assign('next',$next_photo);
		$this->assign('previews',$preview_photos);

		unset($pindex);
		unset($photos);
		unset($album);
		unset($preview_photos);
		$this->display();
	}

	
	
	
	/**
	 +----------------------------------------------------------
	 * 删除照片
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:02:34
	 +----------------------------------------------------------
	 */
	public function delete_photo() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			echo 0;exit;
	  		}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$map['id']		=	intval($_REQUEST['id']);
		$map['albumId']	=	intval($_REQUEST['albumId']);
		$map['classid']	=	$this->classid;
		$result	=	D('ClassAlbum')->deleteClassPhoto($map['id'],$this->classid);
		if($result){
			echo 1;exit;
		}else{
			//删除失败
			echo 0;exit;
		}
	}

	//相册管理
    public function index(){
		$this->display();
    }
    
    /**
     +----------------------------------------------------------
     * 创建相册
     +----------------------------------------------------------
     * @param	name 相册名称
     * @author	小朱 2013-3-5
     +----------------------------------------------------------
     * 创建时间：	2013-3-5 上午09:03:08
     +----------------------------------------------------------
     */
	public function do_create_album() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			echo 0;exit;
	  		}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$name	=	h(t(mStr(trim($_POST['name']),12,'utf-8',false)));
		$classid	=	intval($_POST['classid']);
		if(strlen($name)==0){
			$this->error('相册名称不能为空！');
		}
		if(!$classid){
			$this->error('非法操作！');
		}
		$album	=	D('ClassAlbum');
		//检测相册是否已存在
		$albumId = $album->getField('id',"classid={$classid} AND name='{$name}'");
		if($albumId){
			echo -1;
			exit;
		}
		$album->cTime	=	time();
		$album->mTime	=	time();
		$album->classid	=	$classid;
		$album->name	=	$name;
		$result	= $album->add();
		if($result){
			echo json_encode(array('albumId'=>$result,'albumName'=>$name));
		}else{
			echo 0;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 相册编辑
	 +----------------------------------------------------------
	 * @param	int id  相册id
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:07:30
	 +----------------------------------------------------------
	 */
	public function album_edit() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$id		=	intval($_REQUEST['id']);
		$classid		=$this->classid;
		
		//获取相册信息
		$album		=	D('ClassAlbum')->where(" id='$id' AND classid='$classid' ")->find();
		$this->assign('album',$album);
		$albumlist	=	D('ClassAlbum')->where(" classid='$classid' ")->findAll();
		$this->assign('albumlist',$albumlist);
		if(!$album){
			$this->error('专辑不存在或已被删除！');
		}else{
			//获取图片数据
			$map['albumId']	=	$id;
			$photos		=	D('ClassPhoto')->where($map)->findAll();
			$this->assign('photos',$photos);
		}
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 保存相册编辑信息
	 +----------------------------------------------------------
	 * @param	int albumId 相册id
	 * @param	str album_name 相册名称
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:08:22
	 +----------------------------------------------------------
	 */
	public function do_update_album() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		//相册信息
		$albumId			=	intval($_POST['albumId']);
		$album_name			=	$_POST['album_name'];
		$album_cover		=	intval($_POST['album_cover']);
		$albumDao			=	D('ClassAlbum');
		$photoDao			=	D('ClassPhoto');
		/*   处理图片信息  */
		//解析图片数据
		foreach($_POST['name'] as $k=>$v){
			$new_photos[$k]['name']		=	$v;
			$new_photoids[]	=	$k;
		}
		foreach($_POST['move_to'] as $k=>$v){
			$new_photos[$k]['albumId']	=	$v;
		}
		//对比原始数据，筛选出需要更新的图片
		$photo_ids['id']	=	array('in',$new_photoids);
		$old_photos			=	$photoDao->where($photo_ids)->findAll();
		foreach($old_photos as $k=>$v){
			//如果相册ID和名称都没变化，不需要保存
			$photoid	=	$v['id'];
			if($v['albumId']==$new_photos[$photoid]['albumId'] && $v['name']==$new_photos[$photoid]['name'] ){
				unset($new_photos[$photoid]);
			}
		}
		//保存图片信息
		foreach($new_photos as $k=>$v){
			unset($map);
			$map['classid']		=	$this->classid;
			$map['albumId']		=	$v['albumId'];
			$map['name']		=	$v['name'];
			//相册信息更新
			$photoDao->limit(1)->where("id='$k'")->save($map);
			//重置相册图片数
			$albumDao->updateAlbumPhotoCount($map['albumId']);
		}
	/*   处理相册信息  */

		//重置相册图片数
		$albumDao->updateAlbumPhotoCount($albumId);

		//如果相册封面发生变化
		if($albumInfo['coverImageId']!=$album_cover){
			$album['coverImageId']	=	$album_cover;
			if($coverInfo	=	$photoDao->field('id,savepath')->find($album_cover)){
				$album['coverImagePath'] = $coverInfo['savepath'];
			}
		}
		$album['name']	=	h(t(mStr($album_name,14,'utf-8',false)));
		$album['mTime']=time();
		$result	=	$albumDao->where("id='$albumId'")->save($album);
		//跳转到相册页面
        $this->assign('jumpUrl', U('/Upload/album',array(classid=>$this->classid,id=>$albumId)));
		$this->success('相册编辑成功！');
	}

	/**
	 +----------------------------------------------------------
	 * 照片排序
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:10:10
	 +----------------------------------------------------------
	 */
	public function reorder_photos() {
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 照片编辑
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:10:37
	 +----------------------------------------------------------
	 */
	public function edit_photo_tab() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$map['id']		=	intval($_REQUEST['pid']);
		$map['albumId']	=	intval($_REQUEST['aid']);
		$map['classid']	=	$this->classid;
		$map['isDel']	=	0;
		$photo			=	D('ClassPhoto')->where($map)->find();
		if(!$photo){
			echo "错误的相册信息！";
		}
		$this->assign('photo',$photo);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 照片修改
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:11:12
	 +----------------------------------------------------------
	 */
	public function do_update_photo() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			echo 0;exit;
	  		}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$id		        =	intval($_REQUEST['id']);
		$map['albumId']	=	intval($_REQUEST['albumId']);
		$map['name']	=	h(t($_REQUEST['name']));
		$nextId			=	intval($_REQUEST['nextId']);
		$photoDao       =   D('ClassPhoto');
		$albumDao       =   D('ClassAlbum');
		//图片原信息
		$oldInfo        =	$photoDao->where("id={$id} AND classid={$this->classid}")->field('albumId')->find();
		//更新信息
		$result			=	$photoDao->where("id={$id} AND classid={$this->classid}")->save($map);
		//移动图片则重置相册图片数
		if($map['albumId']!=$oldInfo['albumId']){
			$albumDao->updateAlbumPhotoCount($map['albumId']);
			$albumDao->updateAlbumPhotoCount($oldInfo['albumId']);
		}
		//返回
		if($result){
			$data['result'] = 1;
			$data['message'] = keyWordFilter($map['name']);
			echo json_encode($data);
			// echo 1;//成功
		}else{
			$data['result'] = 0;
			echo json_encode($data);
			// echo 0;
		}
	}

	/**
	 +----------------------------------------------------------
	 * 封面设置
	 +----------------------------------------------------------
	 * @param	int albumId 相册id
	 * @param	int photoId 照片id
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:14:51
	 +----------------------------------------------------------
	 */
	public function set_cover() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			echo 0;exit;
	  		}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$albumId	=	intval($_POST['albumId']);
		$photoId	=	intval($_POST['photoId']);

		$photo		=	D('ClassPhoto')->where("id='$photoId' AND albumId='$albumId' ")->find();
		if($photo){
			$map['coverImageId']	=	$photoId;
			$map['coverImagePath']	=	$photo['savepath'];
			$result		=	D('ClassAlbum')->where(" id='$albumId' ")->save($map);
			if($result){
				//设置成功
				echo "1";
			}else{
				//设置失败 如果封面已是该图片 则也返回该值
				echo "0";
			}
		}else{
			//该图片不存在
			echo "-1";
		}
	}

	/**
	 +----------------------------------------------------------
	 * 删除专辑
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:16:08
	 +----------------------------------------------------------
	 */
	public function delete_album() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
		$map['id']		=	intval($_REQUEST['id']);
		$map['classid']	=	$this->classid;
		$result	= D('ClassAlbum')->deleteAlbum($map['id'],$this->classid);
		if($result){
			$this->assign('jumpUrl', U('/Manage/ManagePhoto',array(classid=>$this->classid)));
			$this->success('删除相册成功~');
		}else{
			//删除失败
			$this->error('删除失败~！');
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 图片排序
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:16:43
	 +----------------------------------------------------------
	 */
	public function album_order() {
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			$this->error('您没有权限访问此功能');
	  		}
		}else if($this->adviser==0){
			$this->error('您没有权限');
		}
        $id     =   intval($_REQUEST['id']);
        $classid=   $this->classid;

        //获取相册信息
        $album  =   D('ClassAlbum')->where(" id='$id' AND classid='$classid' ")->find();
        if(!$album){
            $this->error('专辑不存在或已被删除！');
        }

        $map['albumId'] =   $id;
        $map['classid']  =   $this->classid;
        $map['isDel']   =   0;

        $photos =   D('ClassPhoto')->order('`order` DESC,id DESC')->where($map)->findAll();
        $this->assign('photos',$photos);
        //获取标记数据
        //D('ClassPhotoMarks')->where($map)->findAll();

        $this->setTitle(getUserName($this->uid).'的相册：'.$album['name']);
        $this->assign('aid',$id);
        $this->assign('album',$album);
		$this->display();
	}

	/**
	 +----------------------------------------------------------
	 * 处理保存数据
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:17:18
	 +----------------------------------------------------------
	 */
	public function save_order(){
		if($this->adviser==2){
			if(!$this->_getpower('Manage','ManagePhoto')){	//判断是否有此次访问连接地址的权限
	   			echo 0;exit;
	  		}
		}else if($this->adviser==0){
			echo 0;exit;
		}
		$order	=	explode(',',$_POST['order']);
		$order	=	array_reverse($order);
		$id		=	intval($_POST['id']);
		$dao	=	D('ClassPhoto');

		foreach($order as $key=>$value){
			$condition['id'] = $value;
            $map['order'] = intval($key);
            $result = $dao->where($condition)->save($map);
		}
		//if($result){
			echo 1;
		//}
	}

	/**
	 +----------------------------------------------------------
	 * 图片旋转
	 +----------------------------------------------------------
	 * @author	小朱 2013-3-5
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-5 上午09:17:44
	 +----------------------------------------------------------
	 */
	public function do_rotate(){
		$id     =   $_REQUEST['id'];
		$roll	=	$_REQUEST['roll'];
		$return = false;

		$obj = M('class_photo')->field('savepath')->where("id=$id")->order('`order` DESC,id DESC')->find();
		$savepath=$obj['savepath'];
		$file = substr($savepath,strripos($savepath,"/")+1);
		$path = substr($savepath,0,strripos($savepath,"/")+1);
		$nomalpath = SITE_PATH . '/data/uploads/'. $savepath;
		$smallpath = SITE_PATH . '/data/uploads/'. $path . 'small_' . $file;
		$middlepath = SITE_PATH . '/data/uploads/'. $path . 'middle_' . $file;

		include_once SITE_PATH.'/addons/libs/Image.class.php';
		if($roll==-1){
			$return = Image::rotateChange($nomalpath,-90);
			Image::rotateChange($smallpath,-90);
			Image::rotateChange($middlepath,-90);
		}else{
			$return = Image::rotateChange($nomalpath,90);
			Image::rotateChange($smallpath,90);
			Image::rotateChange($middlepath,90);
		}
		if($return)
			redirect($_SERVER['HTTP_REFERER']);
	}
 	
	
	/**
	 +----------------------------------------------------------
	 * 验证管理权限
	 +----------------------------------------------------------
	 * @param	str session 读取SESSION中是否具有管理权限
	 * @author	小朱 2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 下午03:24:23
	 +----------------------------------------------------------
	 */
	private function _getclassAdviser(){
		$uc_session=arrayKeyTolower($_SESSION['ucInfo']);
		$uc_uid=uc_class_adviser_get_id($this->classid);
		if($uc_uid){
			if($uc_uid==$uc_session['uid']){
				return true;
			}else{
				return false;
			}	
		 }else{
		 	return false;
		 }
	}
	
	private function _ishavePower(){
		$map['identityid']=$this->identityid;
		$map['classid']=$this->classid;
		$map['identitytype']=$this->identitytype;
		$result=D('SetManager')->where($map)->order('mid ASC')->findall();
		return $result;
	}
	
	/**
	 +----------------------------------------------------------
	 * 检验用户是否具有某权限
	 * @param   $mid 权限id
	 * @return	是否具有权限
	 * @author	美美2013-4-1
	 +----------------------------------------------------------
	 * 创建时间：	2013-4-1 上午08:33:57
	 +----------------------------------------------------------
	 */
	private function _getpower($module,$act){
		$result=M('')->table($this->db_prefix.'class_manager as m,'.$this->db_prefix.'class_setmanager as s')
			->where("m.id=s.mid and s.classid=".$this->classid." and s.identityid=".$this->identityid." and m.module='".$module."' and act='".$act."'")
			->find();
		if(!$result){
			return false;
		}else{
			return true;
		}
	}
	
	
	
}