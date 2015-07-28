<?php
//根据存储路径，获取图片真实URL
function get_photo_url($savepath,$type='') {
	return SITE_URL . '/data/uploads/' . $savepath;
}

function resize_photo($photo,$size=700){
	$info = getimagesize($photo);
	$width = $info[0];
	if((int)$width > $size){
		echo 'width="'.$size.'" ';
	}
}

function get_album_name($albumid){
	$album =  D('Album')->find($albumid);
	return $album['name'];
}
//获取相册封面
function get_album_cover($albumId,$album='') {

	//获取相册详细信息
	if(empty($album) || $albumId!=$album['id']){
		$album	=	D('Album')->find($albumId);
	}

	//根据隐私情况，判断相册封面
	if($album['privacy']==4&&(md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'])!=$_COOKIE['album_password_'.$album['id']])){
		//密码可见
		$cover		=	__ROOT__."/apps/photo/Tpl/default/Public/images/photo_mima.gif";
	}elseif($album['privacy']==3){
		//主人可见
		$cover		=	__ROOT__."/apps/photo/Tpl/default/Public/images/photo_zrkj.gif";
	}elseif($album['privacy']==2){
		//显示相册只有他关注的人可见
		$cover		=	__ROOT__."/apps/photo/Tpl/default/Public/images/photo_hykj.gif";
	}else{
		//图片封面
		if(intval($album['photoCount'])>0 && !empty($album['coverImagePath'])){
			$cover	=	get_photo_url($album['coverImagePath'],'m');
		}elseif(intval($album['photoCount'])==0){
			$cover	=	__ROOT__."/apps/photo/Tpl/default/Public/images/photo_zwzp.gif";
		}else{//无设置封面 且有照片 则默认最新一张为封面
			$firstImg = M('photo')->field('savepath')->where("albumId={$album['id']} AND savepath IS NOT NULL and savepath != '' ")->order('`order` DESC,id DESC')->find();
            if($firstImg['savepath'] == ''){
				$cover = __ROOT__."/apps/photo/Tpl/default/Public/images/photo_zwzp.gif";
			}else{
				$cover	  = get_photo_url($firstImg['savepath'],'m');
			}
		}
	}
	return $cover;
}

//获取应用配置参数
function getConfig($key=NULL){
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

function get_privacy($string){
    echo $string;
}
?>