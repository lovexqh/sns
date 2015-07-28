<?php
/**
 +----------------------------------------------------------
 * 根据存储路径，获取缩略图真实URL
 +----------------------------------------------------------
 * @param	$id 视频id
 * @return	String 缩略图真实URL
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:50:27
 +----------------------------------------------------------
 */
function get_picture_url($id,$size='m',$page=3,$stat=0) {
	$config = getConfig();
	if($size!='m')$picmode = '_b.jpg';
	$video = D('Video','video')->where("id=$id")->order('`order` DESC')->find();
	if((int)($video['status'])==1){//状态正常
		$imgpath = $video[serverpath] .$video[savepath] . "/img/" . $video[fcode] . "_".$page.".jpg".$picmode;
	}elseif((int)($video['status'])==0){//正在转换
		$imgpath = SITE_URL."/apps/video/Tpl/default/Public/images/video_convert.gif";
	}else{//无信息
		$imgpath = SITE_URL."/apps/video/Tpl/default/Public/images/video_zwzp.gif";
	}
	return $imgpath;
}

/**
 +----------------------------------------------------------
 * 根据存储路径，获取视频真实URL
 +----------------------------------------------------------
 * @return	String 视频真是路径
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:47:10
 +----------------------------------------------------------
 */
function get_video_url($id,$stat=0) {
	$config = getConfig();
	$video = D('Video','video')->where("id=$id")->order('`order` DESC')->find();
	if($video['status']==1){//状态正常
		$videopath = $video['serverpath'] .$video[savepath].'/'. $video[outfilename];
	}elseif($video['status']==0){//正在转换
		$videopath = SITE_URL."/apps/video/Tpl/default/Public/images/video_convert.mp4";
	}else{//无信息
		$videopath = SITE_URL."/apps/video/Tpl/default/Public/images/video_zwzp.mp4";
	}
	$videopath=base64_encode($videopath);
	return $videopath;
}
/**
 +----------------------------------------------------------
 * 获取微博用户名
 +----------------------------------------------------------
 * @param	int $id 用户id
 * @return	String 用户名
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function getweibouser($id)
{
	$user=model('User')->where('uid='.$id)->find();
	return $user['uname'];
}
/**
 +----------------------------------------------------------
 * 获取文章标题
 +----------------------------------------------------------
 * @param	int $id 文章id
 * @return	String 文章标题
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function get_b_title($id)
{
	$title=M('blog')->where("id=$id")->find();
	return $title['title'];
}
/**
 +----------------------------------------------------------
 * 获取视频标题
 +----------------------------------------------------------
 * @param	int $id 视频id
 * @return	String 视频标题
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function getvideotitle($id)
{
	$title=M('video')->where('id='.$id)->find();
	return $title['name'];
}
/**
 +----------------------------------------------------------
 * 获取资源标题
 +----------------------------------------------------------
 * @param	$id 资源id
 * @return	String 资源标题
 * @author	美美2013-3-5
 +----------------------------------------------------------
 * 创建时间：	2013-3-5 上午06:27:41
 +----------------------------------------------------------
 */
function getrestitle($id)
{
	$title=M('resource')->where('id='.$id)->find();
	return $title['title'];
}
/**
 +----------------------------------------------------------
 * 获取分类标题
 +----------------------------------------------------------
 * @param	int $id 分类
 * @return	String 分类标题
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function getcategory($id)
{
	$title = model('SquareCategory')->where('id='.$id)->find();
	return $title['category_name'];
}
/**
 +----------------------------------------------------------
 * 获取文章分类标题
 +----------------------------------------------------------
 * @param	int $id 文章id
 * @return	String 分类标题
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function getcategory_blog($id)
{
	$category = M('square_blog')->where('blog_id='.$id)->find();
	$title    = model('SquareCategory')->where('id='.$category['square_id'])->find();
	return $title['category_name'];
}
/**
 +----------------------------------------------------------
 * 视频大小转换成M
 +----------------------------------------------------------
 * @param	int $size 视频大小
 * @return	String 转换完成的
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function videosize($size)
{
	$size = $size/(1024*1024);
	$size = number_format($size, 2, '.', '');
	return $size.'M';

}
/**
 +----------------------------------------------------------
 * 视频播放时间
 +----------------------------------------------------------
 * @param	$times 视频播放时间（秒）
 * @return	时间 hh:mm:ss
 * @author	美美2013-3-29
 +----------------------------------------------------------
 * 创建时间：	2013-3-29 上午02:56:11
 +----------------------------------------------------------
 */
function VideoTime($times)
{
	$time = floor($$timesn/3600).":".floor(fmod($times,3600)/60).":".fmod(fmod($times,3600),60);
	return $time;
	
}


//----------------------------------------------------------------获得播放视频object

/**
 +----------------------------------------------------------
 * 获取应用配置参数
 +----------------------------------------------------------
 * @param	int $id 用户id
 * @return	Array 应用配置参数
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:51:39
 +----------------------------------------------------------
 */
function getConfig($key = NULL) {
	$config = model('Xdata')->lget('video');
	$config['category_raws'] || $config['category_raws'] = 6;
	$config['video_raws'] || $config['video_raws'] = 8;
	$config['video_preview'] == 0 || $config['video_preview'] = 1;
	($config['video_max_size'] = floatval($config['video_max_size']) * 1024 * 1024) || $config['video_max_size'] = -1;
	$config['video_file_ext'] || $config['video_file_ext'] = 'jpeg,gif,jpg,png';
	$config['max_flash_upload_num'] || $config['max_flash_upload_num'] = 10;
	$config['open_watermark'] == 0 || $config['open_watermark'] = 1;
	$config['watermark_file'] || $config['watermark_file'] = 'public/images/watermark.png';
	if ($key == NULL) {
		return $config;
	} else {
		return $config[$key];
	}
}

//-----------------------------------------以下为资源中心数据
/**
 +----------------------------------------------------------
 * 获取资源配置信息
 +----------------------------------------------------------
 * @return	Array 资源配置信息
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:58:02
 +----------------------------------------------------------
 */
function getResConfig($key=NULL){
	$config = model('Xdata')->lget('resource');
	$config['limitpage']    || $config['limitpage'] =20;
	$config['server']    || $config['server'] ='';
	$config['credit'] || $config['credit']='score';
	if($key){
		return $config[$key];
	}else{
		return $config;
	}
}
/**
 +----------------------------------------------------------
 * 获取缩略图
 +----------------------------------------------------------
 * @param	$id 资源id
 * @return	String 资源缩略图url
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:58:33
 +----------------------------------------------------------
 */
function getThumb($id)
{
	$data=M('ToolFile')->field("saveaddress,thumb,savepath,savetype")->where("id=$id")->find();
	
	if($data['thumb'])
	{
		$address=$data['saveaddress'].'/'.$data['thumb'];
		
	 }
	 else
	 {
		 
		 $address="__THEME__/square/images/book.jpg";
	 }
	return $address;
}
/**
 +----------------------------------------------------------
 * 获取工具缩略图
 +----------------------------------------------------------
 * @param	$save_path 工具id
 * @return  图片路径
 * @author  liman
 +----------------------------------------------------------
 * 创建时间：	2013-8-24
 +----------------------------------------------------------
 */
function logo_path_to_url($save_path)
{
	$path = SITE_PATH . '/data/uploads/' . $save_path;
	if (file_exists($path))
		return SITE_URL . '/data/uploads/' . $save_path;
	else

		return SITE_URL . '/apps/tool/Tpl/desktop/Public/images/tooldefault.jpg';

}


/**
 +----------------------------------------------------------
 * 获取资源类型
 +----------------------------------------------------------
 * @param	$obj 扩展名
 * @return	String 资源类型
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:59:24
 +----------------------------------------------------------
 */
function getResType($obj)
{
	$word=array('xls','xlsx','xlt','csv','doc','docx','dot','rtf','tml','htm','wpt','html','mht','mhtml','ppt','pptx','pot','vsd');
	$img=array('jpg','gif','png','jpeg','bmp');
	$video=array('flv','rmvb','mp4','tc','3gp');
	$audio=array('mp3');
	if(in_array(strtolower($obj),$word))
	{
		$type="文档";
	}
	else if(in_array(strtolower($obj),$img))
	{
		$type="图片";
	}
	else if(in_array(strtolower($obj),$video))
	{
		$type="视频";
	}
	else if(in_array(strtolower($obj),$audio))
	{
		$type="音频";
	}
	else
	{
		$type="未知格式";
	}
	return $type;
	}
function getDictionary($dictionary){
	$obj=M('category_dictionary')->field('DataName')->where("DataCode='$dictionary'")->find();
	return $obj['DataName'];
}
function getCourse($courseid){
	$obj=M('category_knowledge')->field('Course')->where("CourseID='$courseid'")->find();
	return $obj['Course'];
}
/**
 +----------------------------------------------------------
 * 获取文件大小，保留两位小数
 +----------------------------------------------------------
 * @param	$obj 文件大小
 * @return	String 文件大小（M,KB）
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午03:02:50
 +----------------------------------------------------------
 */
function getSize($obj)
{
	if($obj>1024)
	{
		$size=number_format(($obj/1048576),2,'.','').'MB';
	}
	else
	{
		$size=$obj.'KB';
	}
	return $size;
}

function getAVG($id)
{
  $obj=D('ResScore')->where("id=$id")->avg('score');
  $obj=intval($obj);
  return $obj;
}

function URLformat($param){
	$_GET['stage']	&&	$data['stage']	=	$_GET['stage'];
	$_GET['classified']	&&	$data['classified']	=	$_GET['classified'];
	$_GET['subject']	&&	$data['subject']	=	$_GET['subject'];
	$_GET['edition']	&&	$data['edition']	=	$_GET['edition'];
	$_GET['grade']	&&	$data['grade']	=	$_GET['grade'];
	$_GET['attribute']	&&	$data['attribute']	=	$_GET['attribute'];
	$_GET['type']	&&	$data['type']	=	$_GET['type'];
	$_GET['course']	&&	$data['course']	=	$_GET['course'];
	if($param['classified']=='ElementaryEDU'){
		unset($data['subject']);
		unset($data['edition']);
		unset($data['grade']);
		unset($data['attribute']);
		unset($data['type']);
		unset($data['course']);
	}
	else if($param['classified']=='SpecialEDU' || $param['classified']=='OtherEDU' ){
		unset($data['stage']);
		unset($data['subject']);
		unset($data['edition']);
		unset($data['grade']);
		unset($data['attribute']);
		unset($data['type']);
		unset($data['course']);
	}
	else
	{
		if($param['subject'] && $param['judge']==1)
	    {
		  unset($data['grade']);
		  unset($data['attribute']);
		  unset($data['course']);
	    }
		if($param['subject'] && $param['judge']!=1)
	    {
		  unset($param['judge']);
		  unset($data['edition']);
		  unset($data['grade']);
		  unset($data['attribute']);
		  unset($data['course']);
	    }
		
		if($param['edition'])
	    {
		  unset($data['grade']);
		  unset($data['attribute']);
		  unset($data['course']);
	    }
		if($param['grade'])
	    {
		  unset($data['attribute']);
		  unset($data['course']);
	    }
		if($param['course'])
	    {
		  unset($data['attribute']);
	    }
	}
	foreach($param as $k => $v){
		$data[$k] = $v;	
	}
	return $data;
}

	/**
	 * 
	 +----------------------------------------------------------
	 * 获取字典中中文显示形式
	 +----------------------------------------------------------
	 * @param	$DataCode 英文形式
	 * @return	String 中文形式
	 * @author	美美2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 上午03:01:41
	 +----------------------------------------------------------
	 */
	function getbyDC($DataCode)
	{
		$name=M('category_dictionary')->where("DataCode='".$DataCode."'")->find();
		return $name['DataName'];
	}
	
/**
 +----------------------------------------------------------
 * 根据用户id获取下载资源数
 +----------------------------------------------------------
 * @return	int 下载资源数
 * @author	美美2013-7-12
 +----------------------------------------------------------
 * 创建时间：	2013-7-12 上午02:47:10
 +----------------------------------------------------------
 */
function getdowncount($uid){
	 $down=M('resource_down')->where("uid=".$uid)->count();
	 return $down;
}

//判断导航按钮的选中状态
function getMenuState($type){
	$type = strtolower($type);
	$type = split(',',$type);
	if( in_array(strtolower(ACTION_NAME),$type) ){
		return ' class="on"';
	}
}

?>