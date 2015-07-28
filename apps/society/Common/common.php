<?php/** *  +---------------------------------------------------------- * 返回操作信息 +---------------------------------------------------------- * @param all $msg 返回的信息 * @param int $status 状态 0为成功 * @return return_type <返回类型(void的方法就不用该选项)> * @author Snail 2013-11-22 +---------------------------------------------------------- */function returnMsg($msg,$status=0){    $data['status']=$status;    $data['msg']=$msg;    echo json_encode($data);    exit();} //根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	$path	=	'./data/uploads/' . $savepath;
	if(!file_exists($path))
		$path = './apps/society/Tpl/desktop/Public/images/society_pic.gif';
	return $path;
}//获取票额总数
function getVote($uid,$societyId) {	$param['toUid'] = $uid;	$param['societyId'] = $societyId;	$return = D('SocietyVote')->getVoteCountByParam($param);	return $return;
}//今天是否投票
function voteYesOrNo($uid,$societyId) {	$param['toUid'] = $uid;	$param['fromUid'] = getMid();	$param['societyId'] = $societyId;	$now = date('Y-m-d',time());	$now = strtotime($now);	$param['cTime'] = array('gt',$now);	$return = D('SocietyVote')->getVoteCountByParam($param);	if ($return>0) {		return '<a style="color:#000;">已投票</a>'; 	}else{		return '<a href="javascript:;" onclick="vote('.$uid.','.$societyId.');">投票</a>'; 	}}//是否申请加入该圈子结果function getResultOfApply($societyId){	$param['societyId'] = $societyId;	$param['fromUid']   = getMid();	$param['newsType']  = 1;	$param['result']    = 0;	$result = D('SocietyNews')->getNewsByParam($param);	if ($result) {		return '<div class="word">审核中</div>';	}	$yesOrNo = D('SocietyMember')->checkMemberBySociety($societyId,getMid());	if($yesOrNo==1){		return '<div style="background-color:#4582C5;" class="word">已加入</div>';	}else{		return '<div class="add word" sid="'.$societyId.'">加入</div>';	}}//是否为圈子成员function checkMember($societyId){	$param['id'] =$societyId;
	$param['isDel'] = 0;
	$societyInfo = D('Society')->getSocietyInfoBypara($param);	$yesOrNo = D('SocietyMember')->checkMemberBySociety($societyId,getMid());	if ($yesOrNo==1) {		return 1;	}else{		switch ($societyInfo['type']) {			case 0:				return 0;			break;			case 1:				if($_SESSION['ucInfo']['bjid']==$societyInfo['typeid']){					return 1;				}			break;			case 2:				if($_SESSION['ucInfo']['zyid']==$societyInfo['typeid']){
					return 1;
				}			break;			case 3:				if($_SESSION['ucInfo']['nj']==$societyInfo['typeid']){
					return 1;
				}			break;			case 4:				if($_SESSION['ucInfo']['yxid']==$societyInfo['typeid']){
					return 1;
				}			break;			case 5:				if($_SESSION['ucInfo']['depid']==$societyInfo['typeid']){
					return 1;
				}			break;		}	}} //个人信息function society_getUserInfo($uid){	$userid = $uid;
	$user_link = M('ucenter_user_link')->field('uc_uid')->where('uid='.$userid)->select();
	$uc_user_info = uc_get_user($user_link[0]['uc_uid'], 1);
	$identityid = $uc_user_info['identityID'];
	$identitytype = $uc_user_info['identityType'];
	$ucInfo = uc_get_baseinfo($identityid,$identitytype);
	if($identitytype == 2){		$ucInfo['deptname'] = empty($ucInfo['deptname']) ? '未知部门' : $ucInfo['deptname'];		$ucInfo['zw'] = empty($ucInfo['zw']) ? '未知职务' : $ucInfo['zw'];		return '部门：'.$ucInfo['deptname'].'<br/>'.'职务：'.$ucInfo['zw'];	}else{
		$ucInfo['yxmc'] = empty($ucInfo['yxmc']) ? '未知院系' : $ucInfo['yxmc'];		$ucInfo['bjmc'] = empty($ucInfo['bjmc']) ? '未知班级' : $ucInfo['bjmc'];		return '院系：'.$ucInfo['yxmc'].'<br/>'.'班级：'.$ucInfo['bjmc'];
	}}//获取圈子管理员function getSocietyManager($societyId){	$param['societyId'] = $societyId;	$param['status']    = 2;	$param['isDel']     = 0;	$result = D('SocietyMember')->getSocietyInfoByParam($param);	$result = $result[0];	return '<a href="javascript:void(0);" rel="face" uid="'.$result['uid'].'" class="username" title="'.getUserName($result['uid']).'的个人主页">'.getUserName($result['uid']).'</a>';}//过滤样式function this_tags_html($content){
	$content=preg_replace("/<(\/?style.*?)>/si","",$content); //过滤style标签
	$content=preg_replace("/<(\/?class.*?)>/si","",$content); //过滤style标签
	return $content;
}
//截取function getShort_S($str, $length = 40, $ext = '') {
	$str	=	htmlspecialchars($str);
	$str	=	strip_tags($str);
	$str	=	htmlspecialchars_decode($str);
	$strlenth	=	0;
	$out		=	'';
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
	foreach($match[0] as $v){
		preg_match("/[\xe0-\xef][\x80-\xbf]{2}/",$v, $matchs);
		if(!empty($matchs[0])){
			$strlenth	+=	1;
		}elseif(is_numeric($v)){
			//$strlenth	+=	0.545;  // 字符像素宽度比例 汉字为1
			$strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
		}else{
			//$strlenth	+=	0.475;  // 字符像素宽度比例 汉字为1
			$strlenth	+=	0.5;    // 字符字节长度比例 汉字为1
		}
		if ($strlenth > $length) {
			$output .= $ext.'...';
			break;
		}

		$output	.=	$v;
	}
	return $output;
}//个人信息
function getIdentity($uid){	$userid = $uid;
	$user_link = M('ucenter_user_link')->field('uc_uid')->where('uid='.$userid)->select();
	$uc_user_info = uc_get_user($user_link[0]['uc_uid'], 1);
	$identityid = $uc_user_info['identityID'];
	$identitytype = $uc_user_info['identityType'];
	$ucInfo = uc_get_baseinfo($identityid,$identitytype);	if($identitytype == 1){		return '管理员';	}elseif($identitytype == 2){
		return '老师';	}elseif($identitytype == 3){
		return $ucInfo['nj'];
	}else{		return '--';	}
}
//圈子总人数function getCount($societyId){	$result = D('SocietyMember')->countMember(array('societyId'=>$societyId));	return $result;}//圈子消息数量 function getNewsCount($societyId){	$uid = getMid();	$yesOrNo = D('Societymember')->checkMemberManager($societyId,$uid);	if($yesOrNo==1){		$results = D('SocietyNews')->countSocietyNews($societyId);		$newsArray = array();		foreach ($results as $key => $value) {			if (($value['newsType']==1&&$value['result']==0)||($value['newsType']==2&&$value['result']!=0)||($value['newsType']==3&&$value['result']!=1)) {				$newsArray[] = $value;			}		}		$result = count($newsArray);	}else{		$result = 0;	}	return $result;}function get_album_name($albumid){
	$album =  M('photo_album')->find($albumid);
	return $album['name'];
}?>