<?php
//相册应用 - UploadAction 上传照片 及 处理
class UploadAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
	}
	//普通上传
	public function index() {
		$this->setTitle('普通上传');
		$this->display();
	}
	//其他应用打开本应用的上传
	public function all_index() {
		$this->setTitle('普通上传');
		$this->display();
	}

	//flash上传
	public function flash() {
		$config = getConfig();
		$this->assign($config);
		$this->setTitle('批量上传');
		$this->display();
	}

	//执行单张照片上传
    public function upload_single_pic(){

		$albumId	=	intval($_REQUEST['albumId']);

        //此处修改多加一个参数——徐程亮 13-6-28 上午11:10
		$albumDao   =   D('Album','photo');

		$albumInfo	=	$albumDao->field('id')->find($albumId);
		if(!$albumInfo)echo "0";
		$config     =   getConfig();
		$options['userId']		=	$this->mid;
		$options['allow_exts']	=	$config['photo_file_ext'];
		$options['max_size']    =   $config['photo_max_size'];

        //此处修改——徐程亮 13-6-28 下午12:30
        if($_POST['from'] == 'api'){
            $system_default = model('Xdata')->lget('attach');
            $options['custom_path']	=	date($system_default['attach_path_rule']);				//应用定义的上传目录规则：'Y/md/H/'
            $options['save_path']	=	UPLOAD_PATH.'/'.APP_NAME.'/photo/'.$options['custom_path'];
        }

		//$options['save_photo']['albumId']	=	$albumId;

		$info	=	X('Xattach')->upload('photo',$options);

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

			//为缩略图-小图不加水印，大图、中图加水印
            if($info['info']['extension']!='gif'){
				require_cache(SITE_PATH."/addons/libs/WaterMark/WaterMark.class.php");
				WaterMark::iswater(UPLOAD_PATH.'/'.$bigpic);
				WaterMark::iswater(UPLOAD_PATH.'/'.$middlepic);
			}

			//保存照片信息
			$info['info'] = $this->save_photo($albumId,$info['info']);
			//启用session记录flash上传的照片数，也可以防止意外提交。
			//$upload_count	=	intval($_SESSION['upload_count']);
			//$_SESSION['upload_count']	=	$upload_count + 1;

			//重置相册照片数
			$albumDao->updateAlbumPhotoCount($albumId);

			//上传成功
			echo json_encode($info);
		}else{
			//上传出错
			echo json_encode($info);
		}
    }
	//执行多张照片上传
	public function upload_muti_pic() {

		$albumId	=	intval($_REQUEST['albumId']);
		$albumDao   =   D('Album');
		$albumInfo	=	$albumDao->field('id')->find($albumId);
		if(!$albumInfo)$this->error('不存在的相册ID');
		$config     =   getConfig();
 		$options['userId']		=	$this->mid;
		$options['allow_exts']	=	$config['photo_file_ext'];
		$options['max_size']    =   $config['photo_max_size'];
		//$options['save_photo']['albumId']	=	$albumId;

		//$info		=	$this->api->attach_upload('photo',$options);
		$info	=	X('Xattach')->upload('photo',$options);
		if($info['status']){

			//缩图规格
			$size['small']['x']	=	120;
			$size['small']['y']	=	120;
			$size['middle']['x']	=	465;
			$size['middle']['y']	=	-1; //不限制

			//缩图路径-文件名
			$bigpic		=	$info['info']['savepath'].$info['info']['savename'];
			$smallpic	=	$info['info']['savepath'].'small_'.$info['info']['savename'];
			$middlepic	=	$info['info']['savepath'].'middle_'.$info['info']['savename'];

			//缩图
			include_once SITE_PATH.'/addons/libs/Image.class.php';
			Image::thumb( UPLOAD_PATH.'/'.$bigpic , UPLOAD_PATH.'/'.$smallpic , '' , $size['small']['x'] , $size['small']['y'] );
			Image::thumb( UPLOAD_PATH.'/'.$bigpic , UPLOAD_PATH.'/'.$middlepic , '' , $size['middle']['x'] , ($size['middle']['y']==-1)?'auto':$size['middle']['y'] );

			//为缩略图-小图不加水印，大图、中图加水印
            if($info['info']['extension']!='gif'){
				require_cache(SITE_PATH."/addons/libs/WaterMark/WaterMark.class.php");
				WaterMark::iswater(UPLOAD_PATH.'/'.$bigpic);
				WaterMark::iswater(UPLOAD_PATH.'/'.$middlepic);
			}
			$info['info']['status'] = 
			$info['info'] = $this->save_photo($albumId,$info['info']);
			//记录上传的照片数量
			$upnum	=	count($info['info']);
			//重置相册照片数
			$albumDao->updateAlbumPhotoCount($albumId);

			U('/Upload/muti_edit_photos',array(albumId=>$albumId,upnum=>$upnum),true);
		}else{
			$this->error('上传出错：'.$info['info']);
		}
	}
	//保存照片信息
	public function save_photo($albumId,$attachInfos) {
		//获取相册隐私
		$albumInfo	=	D('Album','photo')->field('privacy')->find($albumId);
		//保存照片附件进入相册 并进行积分操作
		foreach($attachInfos as $k=>$v){
			$photo['attachId']	=	$v['id'];
			$photo['albumId']	=	$albumId;
			$photo['userId']	=	$v['userId'];
			$photo['cTime']		=	time();
			$photo['mTime']		=	time();
			$photo['name']		=	substr($v['name'],'0',strpos($v['name'],'.'));	//去掉后缀名
			$photo['size']		=	$v['size'];
			$photo['savepath']	=	$v['savepath'].$v['savename'];
			$photo['privacy']	=	$albumInfo['privacy'];
			$photo['order']		=	10000;

			$photoid            =   D('Photo')->add($photo);
            //ssq write for society 14-1-10 16:06
			$ucInfo   = get_baseinfo_by_uid ( getUcUid ( $v['userId'] ) ); // 获取用户UIA信息
			$param = array();
			$param['photoid'] = $photoid;
			$param['uid']     = $v['userId'];
			$param['bjid']    = $ucInfo['bjid'];
			$param['zyid']    = $ucInfo['zyid'];
			$param['nj']      = $ucInfo['nj'];
			$param['yxid']    = $ucInfo['yxid'];
			$param['depid']   = $ucInfo['depid'];
	        D('PhotoLink','photo')->addPhotoLink($param);
	        //end
			$attachInfos[$k]['photoId']		=	$photoid;
			$attachInfos[$k]['albumId']		=	$albumId;
			$attachInfos[$k]['url'] = get_photo_url($photo['savepath']);
		}

	 	//计算积分
		X('Credit')->setUserCredit($v['userId'],'add_photo');

		return $attachInfos;
	}

	//上传后执行编辑操作
	public function muti_edit_photos() {

		//判断session,防止意外提交
//		if( intval($_SESSION['upload_count']) > 0 ){
//			$upnum	=	intval($_SESSION['upload_count']);
//			unset($_SESSION['upload_count']);
//		}else{
//			$this->error('上传错误，请正常提交！不要多次点击 "保存照片信息" 按钮！');
//		}
		$upnum		=	intval($_REQUEST['upnum']);
		if($upnum==0)
			$this->error('请至少上传一张照片！');
		$albumId	=	intval($_REQUEST['albumId']);
		$albumDao   =   D('Album');
		$albumInfo	=	$albumDao->find($albumId);

		if(!$albumInfo){
			$this->error('请上传到指定的相册！');
		}

		//公开的相册发布微薄
		if($albumInfo['privacy']<=2){
			$this->assign('publish_weibo',1);
		}
		
		if( $upnum > 0 ) {
		
			$photos		=	D('Photo')->limit($upnum)->order("id DESC")->where("userId='$this->mid'")->findAll();
			$this->assign('photos',$photos);
			$this->assign('album',$albumInfo);
			$this->assign('upnum',$upnum);

			$albumlist	=	$albumDao->where(" userId={$this->uid} ")->findAll();
			$this->assign('albumlist',$albumlist);

			$this->display();

		}else{

			$this->error('上传出错：没有上传任何照片！');
		}
	}

	//保存上传的照片
	public function save_upload_photos() {

		//相册信息
		$albumId		=	intval($_POST['albumId']);
		$album_cover	=	intval($_POST['album_cover']);
		$upnum			=	intval($_POST['upnum']);

		$albumDao       =   D('Album');
		$albumInfo		=	$albumDao->find($albumId);

		if(!$albumInfo){
			$this->error('请先正确选择相册，再上传照片！');
		}
			/*处理照片信息*/
			$photoDao       =   D('Photo');

			//解析照片数据
			foreach($_POST['name'] as $k=>$v){
				$new_photos[$k]['name']		=	$v;
				$new_photoids[]	=	$k;
			}
			foreach($_POST['info'] as $k=>$v){
				$new_photos[$k]['info']	=	$v;
			}


			//对比原始数据，筛选出需要更新的照片
			$photo_ids['id']	=	array('in',$new_photoids);
			$old_photos			=	$photoDao ->where($photo_ids)->findAll();
			foreach($old_photos as $k=>$v){
				//如果相册ID和名称都没变化，不需要保存
				$photoid	=	$v['id'];
				if($v['albumId']==$new_photos[$photoid]['albumId'] && $v['name']==$new_photos[$photoid]['name'] ){
					unset($new_photos[$photoid]);
				}
			}
			//保存照片信息并统计新照片数
			foreach($new_photos as $k=>$v){
				unset($map);
				$map['userId']		=	$this->mid;
				$map['albumId']		=	$albumId;
				$map['name']		=	$v['name'];
				//相册信息更新
				$photoDao->limit(1)->where("id='$k'")->save($map);
				//重置相册照片数
				$albumDao->updateAlbumPhotoCount($map['albumId']);
			}

		/*   处理相册信息  */

			//重置相册照片数
			$albumDao->updateAlbumPhotoCount($albumId);

			//如果相册封
			if($album_cover){
				$album['coverImageId']	=	$album_cover;
				if($coverInfo	=	$photoDao->field('id,savepath')->find($album_cover)){
					$album['coverImagePath']=	$coverInfo['savepath'];
					$albumDao->where("id='$albumId'")->save($album);
				}
			}

		//保存相册数据
		//D('Album')->setAlbumCover($albumId,$album_cover);

		if(intval($_POST['publish_weibo'])==1){
			$newphotoCount = count($new_photoids);
			$photo_ids['albumId'] = $albumId;
			$photoInfo = $photoDao->where($photo_ids)->order('id ASC')->find();
			if(!$photoInfo)$photoInfo = $photoDao->where(array('id'=>array('in',$new_photoids)))->order('id ASC')->find();
			$_SESSION['publish_weibo']=urlencode(serialize(array('count'=>$newphotoCount,'author'=>getUserName($photoInfo['userId']),'title'=>$photoInfo['name'],'url'=>U('photo/Index/photo',array('id'=>$photoInfo['id'],'uid'=>$photoInfo['userId'],'aid'=>$photoInfo['albumId'])),'type'=>1,'type_data'=>$photoInfo['savepath'])));
		}
		$info['info'] = '照片上传成功!';
		$info['status'] = 1;
		$info['name'] = $albumInfo['name'];
		$info['url'] = U('photo/Index/album',array(id=>$albumId,uid=>$this->mid));
		//echo json_encode($info);
		//exit();
		//跳转到相册页面
		$this->redirect('photo/Index/album',array('id'=>$albumId));
		//$this->assign('jumpUrl',U('/Index/album',array(id=>$albumId,uid=>$this->mid)));
		//$this->success('照片上传保存成功！');
	}
}
?>