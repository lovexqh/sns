<?php
function get_photo_url($savepath) {
	$path	=	'./data/uploads/' . $savepath;
	if(!file_exists($path))
		$path = './apps/society/Tpl/desktop/Public/images/society_pic.gif';
	return $path;
}
function getVote($uid,$societyId) {
}
function voteYesOrNo($uid,$societyId) {
	$param['isDel'] = 0;
	$societyInfo = D('Society')->getSocietyInfoBypara($param);
					return 1;
				}
					return 1;
				}
					return 1;
				}
					return 1;
				}
	$user_link = M('ucenter_user_link')->field('uc_uid')->where('uid='.$userid)->select();
	$uc_user_info = uc_get_user($user_link[0]['uc_uid'], 1);
	$identityid = $uc_user_info['identityID'];
	$identitytype = $uc_user_info['identityType'];
	$ucInfo = uc_get_baseinfo($identityid,$identitytype);
	if($identitytype == 2){
		$ucInfo['yxmc'] = empty($ucInfo['yxmc']) ? '未知院系' : $ucInfo['yxmc'];
	}
	$content=preg_replace("/<(\/?style.*?)>/si","",$content); //过滤style标签
	$content=preg_replace("/<(\/?class.*?)>/si","",$content); //过滤style标签
	return $content;
}

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
}
function getIdentity($uid){
	$user_link = M('ucenter_user_link')->field('uc_uid')->where('uid='.$userid)->select();
	$uc_user_info = uc_get_user($user_link[0]['uc_uid'], 1);
	$identityid = $uc_user_info['identityID'];
	$identitytype = $uc_user_info['identityType'];
	$ucInfo = uc_get_baseinfo($identityid,$identitytype);
		return '老师';
		return $ucInfo['nj'];
	}else{
}
//圈子总人数
	$album =  M('photo_album')->find($albumid);
	return $album['name'];
}