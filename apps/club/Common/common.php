<?php
function getClubInfo($clubid, $uid){
	//社团信息
			$value['content'] = mStr(text($value['content']),27);
			$val['title'] = mStr(text($val['title']),20);
		}
}
		$clubinfo['title1'] = mStr(text($clubinfo['title']),7);
	}else{
	return count($applyMember);
function get_photo_url($savepath) {
	//$path	=	'./data/uploads/' . $savepath;
		$path	=	__ROOT__.'/data/uploads/' . $savepath;
	}else{
		$path = './apps/club/Tpl/desktop/Public/images/club_default.png';
	}
	return $path;
}
function get_photo_url_topic($savepath) {
	$path	=	'./data/uploads/' . $savepath;
	if(!file_exists($path))
		$path = './apps/club/Tpl/desktop/Public/images/default.jpg';
	return $path;
}
function isClubMember( $uid='', $clubid='' ){
	$map['clubid'] = $clubid;
	$map['uid'] = $uid;
	$findMember = M('club_member')->where($map)->field('id,type')->find();

	if( !$findMember )
		return false;
	$type = $findMember['type'];
	if( in_array($type, array(1,2,3)) ) {
		return true;
	} else {
		return false;
	}

}
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];

}
	$data['status']=$status;
	$data['msg']=$msg;
	echo json_encode($data);
	exit();
}
function getMemberCount(){
	$count = M('club_member')->where($map)->field('count(distinct uid) as membercount')->select();
	return $count[0]['membercount'];
}