<?php
//获取应用配置参数
function getConfig($key=NULL){
	$config = model('Xdata')->lget('photo');
	$config['album_raws'] || $config['album_raws']=6;
	$config['photo_raws']=9;
	$config['photo_preview']==0 || $config['photo_preview']=1;
	($config['photo_max_size']=floatval($config['photo_max_size'])*1024*1024) || $config['photo_max_size']=-1;
	$config['photo_file_ext'] || $config['photo_file_ext']='jpeg,gif,jpg,png';
	$config['max_flash_upload_num'] || $config['max_flash_upload_num']=10;
	//$config['max_storage_size'] || $config['max_storage_size']=0;
	//$config['max_album_num'] || $config['max_album_num']=0;
	//$config['max_photo_num'] || $config['max_photo_num']=0;
	$config['open_watermark']==0 || $config['open_watermark']=1;
	$config['watermark_file'] || $config['watermark_file']='public/images/watermark.png';
	if($key==NULL){
		return $config;
	}else{
		return $config[$key];
	}
}

/**
 +----------------------------------------------------------
 * 获取课程名称
 +----------------------------------------------------------
 * @param	int $courseid 课程id
 * @return	string  $obj['itemcn'] 课程中文名
 * @author	小朱 2013-4-1
 +----------------------------------------------------------
 * 创建时间：	2013-4-1 上午10:27:18
 +----------------------------------------------------------
 */
function getCourseTitle($courseid){
	$obj=uc_get_xkmc_by_courseid($courseid);
	return $obj['itemcn'];
}

/**
 +----------------------------------------------------------
 * 获取作业类型
 +----------------------------------------------------------
 * @param	int id 作业类型id
 * @return	string $obj['name'] 作业类型名称
 * @author	小朱 2013-4-1
 +----------------------------------------------------------
 * 创建时间：	2013-4-1 下午12:01:01
 +----------------------------------------------------------
 */
function getTName($id) {
	$obj = M('exercise_type')->where('id='.$id)->find();
	return $obj['name'];
}

/**
 +----------------------------------------------------------
 * 根据资源id获取资源名称
 +----------------------------------------------------------
 * @param	$id 资源id
 * @return	资源名称
 * @author	美美2013-3-19
 +----------------------------------------------------------
 * 创建时间：	2013-3-19 上午02:59:43
 +----------------------------------------------------------
 */
function getRName($id) {
	$user	  = M('resource')->where('id='.$id)->find();
	return $user['title'];
}
/**
 +----------------------------------------------------------
 * 根据资源id获取附件名称
 +----------------------------------------------------------
 * @param	$id 附件id
 * @return	附件名称
 * @author	美美2013-3-19
 +----------------------------------------------------------
 * 创建时间：	2013-3-19 上午02:59:43
 +----------------------------------------------------------
 */
function getAttachName($id) {
	$user	  = M('attach')->where('id='.$id)->find();
	return $user['name'];
}
/**
 +----------------------------------------------------------
 * 获取班级管理类型方法名
 +----------------------------------------------------------
 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
 * @return	return_type <返回类型(void的方法就不用该选项)>
 * @author	小朱 2013-4-11
 +----------------------------------------------------------
 * 创建时间：	2013-4-11 下午04:12:48
 +----------------------------------------------------------
 */
function getMagageType($id){
	$type=M('class_manager')->where('id='.$id)->find();
	return $type['act'];
}

/**
 +----------------------------------------------------------
 * 获取附件地址
 +----------------------------------------------------------
 * @param	int $id 附件id
 * @return	return_type <返回类型(void的方法就不用该选项)>
 * @author	小朱 2013-4-9
 +----------------------------------------------------------
 * 创建时间：	2013-4-9 下午05:46:34
 +----------------------------------------------------------
 */
function getAName($id) {
	$res	  = M('attach')->where('id='.$id)->find();
	$str=SITE_URL .'/data/uploads/'.$res['savepath'].'small_'.$res['savename'];
	return $str;
}
function getDatetime($data)
{
   return date('m月d日',strtotime($data)); 	
}

//获取相册封面
function get_album_cover($albumId,$album='',$width=140,$height=140) {

	//获取相册详细信息
	if(empty($album) || $albumId!=$album['id']){
		$album	=	D('ClassAlbum')->find($albumId);
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
			$firstImg = M('class_photo')->field('savepath')->where("albumId={$album['id']}")->order('`order` DESC,id DESC')->find();
			$cover	  = SITE_URL."/thumb.php?w=$width&h=$height&url=".get_photo_url($firstImg['savepath'],'m');
		}
	}
	return $cover;
}
//获取图片路径
function get_photo_url($savepath,$type='') {
	
	return SITE_URL . '/data/uploads/' . $savepath;
}
//根据identityid获取用户名
function get_namebyidentityid($identityid,$identitytype){
	$identity = uc_get_baseinfo($identityid,$identitytype);
	return $identity['xm'];
}
//根据作业类型id获取作业类型
function get_tnamebyid($id){
	$type = M('exercise_type')->where('id='.$id)->find();
	return $type['name'];
}
//身份获取
function get_typename($type){
	if($type==2) return '老师';
	if($type==3) return '学生';
}
//根据uid获取用户名
function get_namebyid($uid){
	$identity = M('user')->where('uid='.$uid)->find();
	return $identity['uname'];
}
//获取课程名称
function get_subjectname($id) {
	$name=M('category_dictionary')->where('id='.$id )->find();
	return $name['DataName'];
}
//获取与Ucenter对应的课程名称
function get_Datacodename($id) {
	$name=M('category_dictionary')->where('DataCode='.$id )->find();
	return $name['DataName'];
}
//获取班级干部名称
function get_positionname($id) {
	$name=M('class_position')->where('id=' . $id )->find();
	return $name['position'];
}
//获取权限名称
function get_powername($id) {
	$name=M('class_manager')->where('id=' . $id )->find();
	return $name['typeName'];
}
//根据用户email获取用户名
function get_username($email) {
	$name=uc_user_uid($email,2);
	return $name;
}
function getUName($n_email) {
     $list	  = D('User')->where("email='$n_email'")->find ();
     return $list['uname'];
    }
function getCourseName($id)
{
	$return=M('category_dictionary')->field('DataName')->where('ID='.$id)->find();
	return $return['DataName'];
}
//班级课程表显示
function getCourse($quantum=1,$coursename=array(),$num,$upnum=0)
{
    $echo;
    $obj=array();
    $course1=array('','早自习一','早自习二','早自习三');
	$course2=array('','第一节','第二节','第三节','第四节','第五节','第六节','第七节','第八节','第九节','第十节','第十一节','第十二节');
	$course4=array('','晚自习一','晚自习二','晚自习三');
	if($num==1) $obj=$course1;
	else if($num==2) $obj=$course2;
	else if($num==3) $obj=$course2;
	else $obj=$course4;
   for($i=1;$i<=$quantum;$i++){ 
	  for($j=0;$j<6;$j++){
		  if($j==0){
			  $echo.='<tr><th scope="row">'.$obj[($i+$upnum)].'</th>';
		  }else{
			  if($i==$quantum){
				  if($j==5)
				  $echo.='<td val="'.$num.'" style=" border-bottom:1px solid #ccc; border-right:0;">';
				  else
				  $echo.='<td val="'.$num.'" style=" border-bottom:1px solid #ccc;">';
			  }else{
				  if($j==5)
				  $echo.='<td val="'.$num.'" style="border-right:0;">';
				  else
				  $echo.='<td val="'.$num.'">';
			  }
			  if(empty($coursename[$i][$j])){
				 $echo.='&nbsp;';
			  }else{
$echo.='<input name="kc" type="hidden" value="'.$coursename[$i][$j].'">'.get_Datacodename($coursename[$i][$j]);
			  }
			  $echo.='</td>';
		  }
	  }
	  $echo.='</tr>';
	}
   return $echo;
}

function getHonorList($id)
{
	 $userlist= D('HonorList')->field('uid')->where("id=".$id)->findall();
	 foreach($userlist as $k=>$v)
	 {
		 $res.="<dl><dt><img src='".getUserFace($v['uid'])."' /></dt><dd>".getUserName($v['uid'])."</dd></dl>";
	 }
	 return $res;
}