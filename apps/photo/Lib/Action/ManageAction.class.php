<?php
//相册应用 - ManageAction 编辑、维护 专辑和照片
class ManageAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
	}

	//删除照片
	public function delete_photo() {
		$map['id']		=	intval($_REQUEST['id']);
		$map['albumId']	=	intval($_REQUEST['albumId']);
		$map['userId']	=	$this->mid;
		$from = $_REQUEST['from'];
		$result	=	D('Album')->deletePhoto($map['id'],$this->mid);
		if($result){
			X('Credit')->setUserCredit($this->mid,'delete_photo');
			$param['photoid'] = $map['id'];
			M('photo_link')->where($param)->delete();
			//删除成功
			echo json_encode(array('status'=>1,'from'=>$from));
			exit;
		}else{
			echo json_encode(array('status'=>0,'from'=>$from));
			exit;
		}
	}

	/**
	 * rickeryu edit 2014-2-8
	 * 创建专辑界面
	 */
	public function create_album_tab(){
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		if(!empty($id)){
			$album = D('Album')->getAlbumInfo($id);
			if($album['userId'] != $this->mid){
				$data['isself'] = false;
			}else{
				$this->assign('album',$album);
			}
		}
		$this->assign($data);
		$this->display();
	}
	/**
	 * rickeryu edit 2014-2-8
	 * 创建专辑处理
	 */
	public function do_create_album() {
		$name	=	h(t(mStr(trim($_POST['name']),12,'utf-8',false)));
		$id =  $_POST['id'];
		if(strlen($name)==0){
			echo json_encode(array('status'=>0,'info'=>'相册名称不能为空！'));
			exit();
		}
		$album	=	D('Album','photo');
		if(empty($id)){
			//检测相册是否已存在
			$albumId = $album->getField('id',"userId={$this->mid} AND name='{$name}'");
			if($albumId){
				echo json_encode(array('status'=>0,'info'=>'相册名称已经存在！'));
				exit();
			}
			$data['cTime']	=	time();
			$data['userId']	=	$this->mid;
		}else{
			//检测相册是否已存在
			$albumId = $album->getAlbumInfo($id);
			if($albumId['userId'] != $this->mid){
				echo json_encode(array('status'=>0,'info'=>'你没有修改当前相册的权限！'));
				exit();
			}
		}
		$data['mTime']	=	time();
		$data['name']	=	$name;
		$data['privacy']	=	intval($_POST['privacy']);

		//设置相册密码
		if(intval($_POST['privacy'])==4){
			$data['privacy_data']	=	t($_POST['privacy_data']);
		}
		if(empty($id)){
			//添加操作
			$result	= $album->add($data);
			if($result){
				X('Credit')->setUserCredit($this->mid,'add_album');
				//此处修改——徐程亮 13-6-28 上午11:36
				if($_POST['from'] == 'api'){
					return array("albumId"=>$result,"albumName"=>$name);
				}
				echo json_encode(array("status"=>1,'type'=>'add',"albumId"=>$result,"albumName"=>$name));
			}else{
				//此处修改——徐程亮 13-6-28 下午1:22
				if($_POST['from'] == 'api'){
					return 0;
				}
				echo json_encode(array('status'=>0,'info'=>'添加相册失败！'));
				exit();
			}
		}else{
			//更新操作
			$result	= $album->where('id = '.$id)->save($data);
			D('Photo')->updatePhoto($id,intval($_POST['privacy']));			
			if($result){
				echo json_encode(array('status'=>1,'type'=>'update','info'=>'更新相册成功！',"albumId"=>$result,"albumName"=>$name));
				exit();
			}else{
				echo json_encode(array('status'=>0,'info'=>'更新相册失败！'));
				exit();
			}
		}
	}

	/**
	 * rickeryu edit 2014-2-8
	 * 删除专辑
	 */
	public function delete_album() {
		$map['id']		=	intval($_REQUEST['id']);
		$map['userId']	=	$this->mid;

		$result	= D('Album')->deleteAlbum($map['id'],$this->mid);
		if($result){
			//删除成功
			X('Credit')->setUserCredit($this->mid,'delete_album');
			echo json_encode(array('status'=>1,'info'=>'删除相册成功！'));
			exit();
		}else{
			echo json_encode(array('status'=>0,'info'=>'删除相册成功！'));
			exit();
		}
	}

	//编辑专辑
	public function album_edit() {

		$id		=	intval($_REQUEST['id']);
		$uid	=	$this->mid;

		//获取相册信息
		$album		=	D('Album')->where(" id='$id' AND userId='$uid' ")->find();
		$this->assign('album',$album);

		$albumlist	=	D('Album')->where(" userId='$uid' ")->findAll();
		$this->assign('albumlist',$albumlist);

		if(!$album){

			$this->error('专辑不存在或已被删除！');
		}else{

			//获取照片数据
			$map['albumId']	=	$id;
			$photos		=	D('Photo')->where($map)->findAll();
			$this->assign('photos',$photos);

		}

		$this->display();
	}

	//保存相册编辑信息
	public function do_update_album() {

		//相册信息
		$albumId			=	intval($_POST['albumId']);
		$album_name			=	$_POST['album_name'];
		$album_cover		=	intval($_POST['album_cover']);
		$album_privacy		=	intval($_POST['album_privacy']);
		$album_privacy_data	=	$_POST['album_privacy_data'];

		$albumDao		=	D('Album');
		$photoDao		=	D('Photo');
		//$photoIndexDao	=	D('AlbumPhoto');

		//获取相册信息
		$albumInfo		=	$albumDao->where("id='$albumId' AND userId='$this->mid' ")->find();
		if(!$albumInfo){
			$this->error('你没有权限编辑该相册！');
		}

		/*   处理照片信息  */

			//解析照片数据
			foreach($_POST['name'] as $k=>$v){
				$new_photos[$k]['name']		=	$v;
				$new_photoids[]	=	$k;
			}
			foreach($_POST['move_to'] as $k=>$v){
				$new_photos[$k]['albumId']	=	$v;
			}


			//对比原始数据，筛选出需要更新的照片
			$photo_ids['id']	=	array('in',$new_photoids);
			$old_photos			=	$photoDao->where($photo_ids)->findAll();
			foreach($old_photos as $k=>$v){
				//如果相册ID和名称都没变化，不需要保存
				$photoid	=	$v['id'];
				if($v['albumId']==$new_photos[$photoid]['albumId'] && $v['name']==$new_photos[$photoid]['name'] ){
					unset($new_photos[$photoid]);
				}
			}

			//保存照片信息
			foreach($new_photos as $k=>$v){
				unset($map);
				$map['userId']		=	$this->mid;
				$map['albumId']		=	$v['albumId'];
				$map['name']		=	$v['name'];
				$map['privacy']		=	$album_privacy;
				//相册信息更新
				$photoDao->limit(1)->where("id='$k'")->save($map);
				//重置相册照片数
				$albumDao->updateAlbumPhotoCount($map['albumId']);

				//相册索引更新
				//$index['albumId']	=	$v['albumId'];
				//$photoIndexDao->limit(1)->where("albumId='".$v['albumId']."' AND photoId='".$k."'")->save($index);
			}

		/*   处理相册信息  */

			//重置相册照片数
			$albumDao->updateAlbumPhotoCount($albumId);

			//如果相册封面发生变化
			if($albumInfo['coverImageId']!=$album_cover){
				$album['coverImageId']	=	$album_cover;
				if($coverInfo	=	$photoDao->field('id,savepath')->find($album_cover)){
					$album['coverImagePath'] = $coverInfo['savepath'];
				}
			}

			//如果相册隐私发生变化
			if( $albumInfo['privacy'] != $album_privacy ){
				$album['privacy']	=	$album_privacy;
				//更新该相册下所有照片的隐私
				unset($map);
				$map['privacy']		=	$album_privacy;
				$photoDao->where("albumId='$albumId'")->save($map);
			}

			//如果相册隐私数据发生变化
			if( $albumInfo['privacy_data'] != $album_privacy_data ){
				$album['privacy_data']	=	h(t($album_privacy_data));
			}

			$album['name']	=	h(t(mStr($album_name,14,'utf-8',false)));

			$result	=	$albumDao->where("id='$albumId'")->save($album);

		//跳转到相册页面
        $this->assign('jumpUrl', U('/Index/album',array(id=>$albumId,uid=>$this->mid)));
		$this->success('相册编辑成功！');
	}

	//照片排序
	public function reorder_photos() {
		$this->display();
	}

	//编辑照片
	public function edit_photo_tab() {
		$map['id']		=	intval($_REQUEST['pid']);
		$map['albumId']	=	intval($_REQUEST['aid']);
		$map['userId']	=	$this->mid;
		$map['isDel']	=	0;
		$photo			=	D('Photo')->where($map)->find();
		$album = M('photo_album')->where('id = '.$map['albumId'].' AND isDel = 0 ')->find();
		$this->assign('album',$album);
		if(!$photo){
			echo "错误的相册信息！";
		}
		$this->assign('photo',$photo);
		$this->display();
	}

	//执行照片修改操作
	public function do_update_photo() {
		$id		        =	intval($_REQUEST['id']);
		$map['albumId']	=	intval($_REQUEST['albumId']);
		$map['name']	=	h(t($_REQUEST['name']));
		$album_cover =  isset($_REQUEST['album_cover']) ? intval($_REQUEST['album_cover']) : '';
		$photoDao       =   D('Photo');
		$albumDao       =   D('Album');

		//如果相册封
		if(!empty($album_cover)){
			$album['coverImageId']	=	$album_cover;
			if($coverInfo	=	$photoDao->field('id,savepath')->find($album_cover)){
				$album['coverImagePath']=	$coverInfo['savepath'];
				$realbum = $albumDao->where("id=".$map['albumId'])->save($album);
			}
		}
		
		// Bug修改  ssq  14-1-14  获取目的相册的浏览权限
		$album = $albumDao->where("id={$map['albumId']}")->field('privacy')->find();
		$map['privacy'] = $album['privacy'];
		
		//照片原信息
		$oldInfo        =	$photoDao->where("id={$id} AND userId={$this->mid}")->field('albumId')->find();
		//更新信息
		$result			=	$photoDao->where("id={$id} AND userId={$this->mid}")->save($map);
		//移动照片则重置相册照片数
		if($map['albumId']!=$oldInfo['albumId']){
			$albumDao->updateAlbumPhotoCount($map['albumId']);
			$albumDao->updateAlbumPhotoCount($oldInfo['albumId']);
		}
		//返回
		if($result || $realbum){
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

	//设置封面
	public function set_cover() {
		$albumId	=	intval($_POST['albumId']);
		$photoId	=	intval($_POST['photoId']);

		$photo		=	D('Photo')->where("id='$photoId' AND albumId='$albumId' ")->find();
		if($photo){
			$map['coverImageId']	=	$photoId;
			$map['coverImagePath']	=	$photo['savepath'];
			$result		=	D('Album')->where(" id='$albumId' ")->save($map);
			if($result){
				//设置成功
				echo "1";
			}else{
				//设置失败 如果封面已是该照片 则也返回该值
				echo "0";
			}
		}else{
			//该照片不存在
			echo "-1";
		}
	}

	//照片排序
	public function album_order() {

        $id     =   intval($_REQUEST['id']);
        $uid    =   $this->mid;

        //获取相册信息
        $album  =   D('Album')->where(" id='$id' AND userId='$uid' ")->find();
        if(!$album){
            $this->error('专辑不存在或已被删除！');
        }

        $map['albumId'] =   $id;
        $map['userId']  =   $uid;
        $map['isDel']   =   0;

        $photos =   D('Photo')->order('`order` DESC,id DESC')->where($map)->findAll();
        $this->assign('photos',$photos);
        //获取标记数据
        //D('PhotoMarks')->where($map)->findAll();

        $this->setTitle(getUserName($this->uid).'的相册：'.$album['name']);
        $this->assign('aid',$id);
        $this->assign('album',$album);
		$this->display();
	}

	//保存照片排序
	public function save_order(){
		$order	=	explode(',',$_POST['order']);
		$order	=	array_reverse($order);
		$id		=	intval($_POST['id']);
		$dao	=	D('Photo');

		foreach($order as $key=>$value){
			$condition['id'] = $value;
            $map['order'] = intval($key);
            $result = $dao->where($condition)->save($map);
		}
		//if($result){
			echo 1;
		//}
	}

	//旋转照片
	public function do_rotate(){
		$id     =   $_REQUEST['id'];
		$roll	=	$_REQUEST['roll'];
		$return = false;

		$obj = M('photo')->field('savepath')->where("id=$id")->order('`order` DESC,id DESC')->find();
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

	//设为头像
	/*public function set_face() {
		//暂时先这么写，应该加到API接口里面去。
		$path		=	SITE_PATH."/data/thumb/";
		$filename	=	$path.'xxx_s.jpg';
        $face_path  =	getFacePath($this->mid);
        $middle_name =	$face_path."/".$this->mid."_middle_face.jpg";     //中图
		imagejpeg($dst_r,$middle_name);  //生成中图
		imagedestroy($dst_r);
		imagedestroy($img_r);

		$small_name  = $face_path."/".$this->mid."_small_face.jpg";     //小图
		vendor("yu_image");
		$img = new yu_image();
		$img->param($middle_name)->thumb($small_name,$small_w,$small_h,0);        //缩出小图

        //添加一条动态
		$body_data["src"] = getUserFace($this->mid);
		$this->api->feed_publish("head",$title_data,$body_data);
	}*/
}
?>