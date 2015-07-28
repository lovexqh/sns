<?php
/**
 +----------------------------------------------------------
 * 根据课程ID获取课程名
 +----------------------------------------------------------
 * @param	int		$id		课程ID
 * @return	string	返课程名
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function get_sub_id($id) {
	$name=M('category_dictionary')->where('id='.$id)->find();
	return $name['DataName'];
}

/**
 +----------------------------------------------------------
 * 根据班级id获取班级名
 +----------------------------------------------------------
 * @param	int		$id		课程ID
 * @return	string	返班级名
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function get_class_id($id) {
	$class=uc_dept_get_id($id);
	return $class['DepartName'];
}

/**
 +----------------------------------------------------------
 * 是否已设置头像
 +----------------------------------------------------------
 * @param	int		$id		课程ID
 * @return	bool	返true or false
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function isSetAvatar($uid) {
	return is_file(SITE_PATH . '/data/uploads/avatar/' . $uid . '/small.jpg');
}

/**
 +----------------------------------------------------------
 * 获取微广播条数
 +----------------------------------------------------------
 * @param	int		$uid	用户ID
 * @return	int		返数值 
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function getMiniNum($uid) {
	return M('weibo')->where('uid=' . $uid . ' AND isdel=0')->count();
}

/**
 +----------------------------------------------------------
 * 获取关注数
 +----------------------------------------------------------
 * @param	int		$uid	用户ID
 * @return	int		返数值 
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function getUserFollow($uid) {
	$count['following'] = M('weibo_follow')->where("uid=$uid AND type=0")->count();
	$count['follower'] = M('weibo_follow')->where("fid=$uid AND type=0")->count();
	return $count;
}
 
/**
 +----------------------------------------------------------
 * 短地址
 +----------------------------------------------------------
 * @param	string		$url	源地址
 * @return	string		返短地址
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function getContentUrl($url) {
	return getShortUrl($url[1]) . ' ';
}

/**
 +----------------------------------------------------------
 * 登录页微广播表情解析
 +----------------------------------------------------------
 * @param	string $content 微博内容
 * @return	string 解析后的内容
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function login_emot_format($content) {
	return preg_replace_callback('/(?:#[^#]*[^#^\s][^#]*#|(\[.+?\]))/is', 'replaceEmot', $content);
}

/**
 +----------------------------------------------------------
 * 根据数组返回tree
 +----------------------------------------------------------
 * @param	array $deptlist 二维数组
 * @return	array tree数组
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:48:18
 +----------------------------------------------------------
 */
function get_tree($deptlist) {
	$list = array ();
	include_once SITE_PATH . '/addons/libs/Tree.class.php';
	$tree = new Tree();
	$nums = count($deptlist);
	foreach ($deptlist as $key => $deptobj) {
		$tree->setNode($deptobj['DeptID'], $deptobj['UpDeptID'], $deptobj);
	}
	$childs = $tree->getChilds();

	$uplevel = "";
	foreach ($childs as $key => $vid) {
		$one = $tree->getValue($vid);
		$one['level'] = $tree->getLayer($vid);

		if ($one['level'] == $uplevel) {
			if ($key == $nums -1) {
				$t = $one['level'];
				while ($t >= 0) {
					if ($t == 0) {
						$one['style2'] .= "</li>";
					}
					if ($t > 0) {
						$one['style2'] .= "</li></ul>";
					}
					$t = $t -1;
				}
			}

			$one['style1'] = "</li><li>";
		}

		if ($uplevel == "") {
			$one['style1'] = "<li>";
		}
		if ($one['level'] == $uplevel && $one['level'] == 0 && $uplevel == "" && $key != 0) {
			$one['style1'] = "</li><li>";
		}

		if ($one['level'] > $uplevel) {
			if ($key == $nums -1) {
				$t = $one['level'];
				while ($t >= 0) {
					if ($t == 0) {
						$one['style2'] .= "</li>";
					}
					if ($t > 0) {
						$one['style2'] .= "</li></ul>";
					}
					$t = $t -1;
				}
			}
			$one['style1'] = "<ul><li>";
		}

		if ($one['level'] < $uplevel) {

			$u = $uplevel;
			$t = $one['level'];
			while (($u - $t) >= 0) {
				if (($u - $t) == 0) {
					$one['style1'] .= "</li><li>";
				}
				if (($u - $t) > 0) {
					$one['style1'] .= "</li></ul>";
				}
				$u = $u -1;
			}

			if ($key == $nums -1) {
				while ($t >= 0) {
					if ($t == 0) {
						$one['style2'] .= "</li>";
					}
					if ($t > 0) {
						$one['style2'] .= "</li></ul>";
					}
					$t = $t -1;
				}
			}
		}

		$uplevel = $one['level'];
		$list[] = $one;
	}
	return $list;
}

/**
 +----------------------------------------------------------
 * 字符串切割函数
 +----------------------------------------------------------
 * @param	string		$str		要切割的字符串
 * @param	int			$length		要切割的长度
 * @param	string		$suffixStr	切割后的尾注
 * @param	int			$start		开始位置
 * @param	string		$tags		字符串可能包含的HTML标签
 * @param	float		$zhfw		用来修正中英字宽参数
 * @param	string		$charset	字符编码
 * @return	String		切割后的字符串
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:53:57
 +----------------------------------------------------------
 */
function wsubstr($str, $length = 0, $suffixStr = "...", $start = 0, $tags = "div|span|p|img|a|object|embed", $zhfw = 0.9, $charset = "utf-8") {
	//author: lael
	//blog: http://hi.baidu.com/lael80

	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

	$zhre['utf-8'] = "/[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$zhre['gb2312'] = "/[\xb0-\xf7][\xa0-\xfe]/";
	$zhre['gbk'] = "/[\x81-\xfe][\x40-\xfe]/";
	$zhre['big5'] = "/[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

	//下面代码还可以应用到关键字加亮、加链接等，可以避免截断HTML标签发生
	//得到标签位置
	$tpos = array ();
	preg_match_all("/<(" . $tags . ")([\s\S]*?)>|<\/(" . $tags . ")>/ism", $str, $match);
	$mpos = 0;
	for ($j = 0; $j < count($match[0]); $j++) {
		$mpos = strpos($str, $match[0][$j], $mpos);
		$tpos[$mpos] = $match[0][$j];
		$mpos += strlen($match[0][$j]);
	}
	ksort($tpos);

	//根据标签位置解析整个字符
	$sarr = array ();
	$bpos = 0;
	$epos = 0;
	foreach ($tpos as $k => $v) {
		$temp = substr($str, $bpos, $k - $epos);
		if (!empty ($temp))
			array_push($sarr, $temp);
		array_push($sarr, $v);
		$bpos = ($k +strlen($v));
		$epos = $k +strlen($v);
	}
	$temp = substr($str, $bpos);
	if (!empty ($temp))
		array_push($sarr, $temp);

	//忽略标签截取字符串
	$bpos = $start;
	$epos = $length;
	for ($i = 0; $i < count($sarr); $i++) {
		if (preg_match("/^<([\s\S]*?)>$/i", $sarr[$i]))
			continue; //忽略标签

		preg_match_all($re[$charset], $sarr[$i], $match);

		for ($j = $bpos; $j < min($epos, count($match[0])); $j++) {
			if (preg_match($zhre[$charset], $match[0][$j]))
				$epos -= $zhfw; //计算中文字符
		}

		$sarr[$i] = "";
		for ($j = $bpos; $j < min($epos, count($match[0])); $j++) { //截取字符
			$sarr[$i] .= $match[0][$j];
		}
		$bpos -= count($match[0]);
		$bpos = max(0, $bpos);
		$epos -= count($match[0]);
		$epos = round($epos);
	}

	//返回结果
	$slice = join("", $sarr); //自己可以加个清除空html标签的东东
	if ($slice != $str)
		return $slice . $suffixStr;
	return $slice;
}

/**
 +----------------------------------------------------------
 * 根据社团ID返回社团所有数据
 +----------------------------------------------------------
 * @param	int	$id	社团ID
 * @return	array 社团所有数据
 * @author	小波
 +----------------------------------------------------------
 * 创建时间：2013-3-5 15:59:28
 +----------------------------------------------------------
 */
function getGroupInfo($id){
	if(intval($id)){
		$map['id'] = $id;
		$data = D('Group','group')->where($map)->find();
		if($data)
			$data['member'] = D('Member','group')->where('gid = '.$id)->order('cTime desc')->findAll();
		return $data;
		
	}
}
/**
 +----------------------------------------------------------
 * 根据appname 来获取大桌面中的icoid
 +----------------------------------------------------------
 * @author 小波 2013-6-29
 +----------------------------------------------------------
 */
function getIcoid($appName){
	global $ts;
	if (empty($appName)) {
		return 0;
	}
	$uid = $ts['user']['uid'];
	$icoid = M()->table( C('DB_PREFIX') . 'dsk_apps D' )
	->join( 'LEFT JOIN '.C('DB_PREFIX') . 'app A ON A.app_id=D.oid LEFT JOIN '.C('DB_PREFIX').'dsk_icos I ON D.appid=I.oid' )
	->where( "A.app_name='{$appName}' AND ( I.uid='{$uid}' OR I.uid='-1' OR I.uid='0' )" )
	->getField('I.icoid');
	return $icoid;
}

/**
 +----------------------------------------------------------
 * 获取唯一昵称
 +----------------------------------------------------------
 * @author 小波 2013-6-29
 +----------------------------------------------------------
 */
function randUName($uname){
	$_tmp = M("user")->where("uname like '{$uname}%'")->findAll();
	//将所有名字生成1维数组
	if ($_tmp) $_extend_names = getSubByKey($_tmp,'uname');
	$username = $uname . '_' . rand(100,999);
	if (in_array($username, $_extend_names)) {
		$username = randUName($uname);
	}
	return $username;
}
/**
 +----------------------------------------------------------
 * 根据存储路径，获取缩略图真实URL
 +----------------------------------------------------------
 * @param	$id 视频id
 * @return	String 缩略图真实URL
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:46:25
 +----------------------------------------------------------
 */
function get_picture_url($id,$stat=0,$video=false) {
	$config = getConfig();
	if(!$video){
		$video = D('Video','video')->where("id=$id")->order('`order` DESC')->find();
	}
	if($video['status']==1){//状态正常
		if(empty($video[thumb])){
			$video[thumb] = 3;
		}
		$imgpath = SITE_URL . "/thumb.php?url=" . $video[serverpath] .$video[savepath] . "/img/" . $video[fcode] . "_".$video[thumb].".jpg";
	}elseif($video['status']==0){//正在转换
		$imgpath = SITE_URL."/apps/video/Tpl/default/Public/images/video_convert.gif";
	}else{//无信息
		$imgpath = SITE_URL."/apps/video/Tpl/default/Public/images/video_zwzp.gif";
	}
	//$imgpath=base64_encode($imgpath);
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
 * 获取应用配置参数
 +----------------------------------------------------------
 * @return	Array 应用配置参数
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:49:25
 +----------------------------------------------------------
 */
function getConfig($key = NULL) {
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
//获取相册封面
function get_album_cover($albumId,$album='',$width=140,$height=140) {
	 
	//获取相册详细信息
	if(empty($album) || $albumId!=$album['id']){
		$album	=	M('photo_album')->find($albumId);
	}
	
	//根据隐私情况，判断相册封面
	if($album['privacy']==4&&(md5($album['privacy_data'].'_'.$album['id'].'_'.$album['userId'])!=$_COOKIE['album_password_'.$album['id']])){
		//密码可见
		$cover		=	SITE_URL."/thumb.php?w=$width&h=$height&url=./apps/photo/Tpl/default/Public/images/photo_mima.gif";
	}elseif($album['privacy']==3){
		//主人可见
		$cover		=	SITE_URL."/thumb.php?w=$width&h=$height&url=./apps/photo/Tpl/default/Public/images/photo_zrkj.gif";
	}elseif($album['privacy']==2){
		//显示相册只有他关注的人可见
		$cover		=	SITE_URL."/thumb.php?w=$width&h=$height&url=./apps/photo/Tpl/default/Public/images/photo_hykj.gif";
	}else{
		//图片封面
		if(intval($album['photoCount'])>0 && !empty($album['coverImagePath'])){
			$cover	=	SITE_URL."/thumb.php?w=$width&h=$height&url=".get_photo_url($album['coverImagePath'],'m');
		}elseif(intval($album['photoCount'])==0){
			$cover	=	SITE_URL."/thumb.php?w=$width&h=$height&url=./apps/photo/Tpl/default/Public/images/photo_zwzp.gif";
		}else{//无设置封面 且有照片 则默认最新一张为封面
			$firstImg = M('photo')->field('savepath')->where("albumId={$album['id']}")->order('`order` DESC,id DESC')->find();
			$cover	  = SITE_URL."/thumb.php?w=$width&h=$height&url=".get_photo_url($firstImg['savepath'],'m');
		}
	}
	return $cover;
}
//根据存储路径，获取图片真实URL
function get_photo_url($savepath,$type='') {
	/*if($type!=''){
		$file = substr($savepath,strripos($savepath,"/")+1);
	$path = substr($savepath,0,strripos($savepath,"/")+1);
	if($type=='s'){
	$imgpath = SITE_URL . '/data/uploads/'. $path . 'small_' . $file;
	}
	if($type=='m'){
	$imgpath = SITE_URL . '/data/uploads/'. $path . 'middle_' . $file;
	}
	}else{
	$imgpath = SITE_URL . '/data/uploads/' . $savepath;
	}
	if(@getimagesize($imgpath)){
	return $imgpath;
	}else*/
	return SITE_URL . '/data/uploads/' . $savepath;
}
function get_albumName_by_id($albumId){
	$albumName = M('photo_album')->field('name')->where('id='.$albumId)->find();
	return $albumName['name'];

}


/**
 +----------------------------------------------------------
 * 获取字典表里资源名称
 +----------------------------------------------------------
 * @param	String $dictionary 字典表里名称
 * @return	String 字典表里中文名
 * @author	美美2013-3-4
 +----------------------------------------------------------
 * 创建时间：	2013-3-4 上午03:07:34
 +----------------------------------------------------------
 */
function getDictionary($dictionary){
	$obj=M('category_dictionary')->field('DataName')->where("DataCode='$dictionary'")->find();
	return $obj['DataName'];
}

/**
 +----------------------------------------------------------
 * 获取该资源收藏数
 +----------------------------------------------------------
 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return	return_type <返回类型(void的方法就不用该选项)>
 * @author	小朱 2013-8-2
 +----------------------------------------------------------
 * 创建时间：	2013-8-2 上午06:36:09
 +----------------------------------------------------------
 */
function get_collect($id){
	$collect = M('favorite')->field('count(id) as count')->where("appname='resource' and fid=".$id)->find();
	return $collect['count'];
}

/**
 +----------------------------------------------------------
 * 根据ID获取select值
 +----------------------------------------------------------
 * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author 小伟 2013-4-2
 +----------------------------------------------------------
 */
function getSelectItem($id){
	$result	=	M('category_dictionary')->where("`id`	=	'".$id."'")->find();
	return $result['DataCode'];
	
	
}

/**
 +----------------------------------------------------------
 * 获取教研URL
 +----------------------------------------------------------
 * @param $id
 * @return return_type <返回类型(void的方法就不用该选项)>
 * @author 小伟 2013-5-24
 +----------------------------------------------------------
 */
function getTeachingName($id){
	$teaching	=	D('Teach')->where(" meetingId = '$id'")->find();
	return $teaching['meetingName'];
}
function getNum($gid){
	$nums	=	M('teach_group_member')->where("gid='$gid' and status in (1,2) ")->select();
	return	count($nums);
}
function getSchoolName($id){
	$name	=	get_school_by_id($id);
	return	$name[0]['xxmc'];
}

function logo_path_to_url($save_path)
{
	$path = SITE_PATH . '/data/uploads/' . $save_path;
	if (file_exists($path))
		return SITE_URL . '/data/uploads/' . $save_path;
	else

		return SITE_URL . '/apps/tool/Tpl/desktop/Public/images/tooldefault.jpg';

}
