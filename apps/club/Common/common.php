<?phpfunction compress($buffer) {//去除文件中的注释	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);	return $buffer;}function getContentUrl($url){	return getShortUrl( $url[1] ).' ';}/** * @title  获取社团的信息 * @description  * @param * @return * @author	xiawei 2013-11-27 */
function getClubInfo($clubid, $uid){	if(!$clubid || !$uid){		$this->error("操作失败!");	}
	//社团信息	$headData['clubInfo'] = getClubInfoById($clubid);	$headData['clubInfo']['topicNum'] = count(D('ClubTopic')->getTopicNumByClubid($clubid));	$headData['clubInfo']['noticeNum'] = count(D('ClubNotice')->getAllNotice($clubid));	//待审核的成员	$headData['applyMemberCount'] = getApplyMemberCount($clubid);	//社团公告	$headData['notice'] = getClubNotice($clubid);	foreach ($headData['notice'] as &$value){		$value['content'] = htmlspecialchars($value['content']);		if( strlen($value['content'])>70 ){
			$value['content'] = mStr(text($value['content']),27);		}	}	//该成员在社团中的信息	$headData['member'] = getMemberinfoInClub($clubid, $uid);	//社团风采	$headData['eventShow'] = D('ClubTopic')->getShowEvent($clubid,6);	$headData['eventCount'] = count($headData['eventShow']);	foreach ($headData['eventShow'] as &$val){		$clubinfo = D('Club')->getClubById($val['clubid']);		$val['clubname'] = $clubinfo['title'];		$val['content'] = text($val['content']);		if( strlen($val['title'])>20 ){
			$val['title'] = mStr(text($val['title']),20);
		}	}	return $headData;
}//社团信息function getClubInfoById($clubid){	$clubinfo = D('Club')->getClubById($clubid);	$clubinfo['description'] = htmlspecialchars($clubinfo['description']);	if( strlen($clubinfo['title'])>7 ){
		$clubinfo['title1'] = mStr(text($clubinfo['title']),7);
	}else{		$clubinfo['title1'] = $clubinfo['title'];	}	return $clubinfo;}//待审核的成员function getApplyMemberCount($clubid){	$applyMember = D('ClubMember')->getApplyMember($clubid);
	return count($applyMember);}//社团公告function getClubNotice($clubid){	return D('ClubNotice')->getNoticeList($clubid);}//该成员在社团中的信息function getMemberinfoInClub($clubid, $uid){	return D('ClubMember')->getMemberInfoInClub($clubid, $uid);}//根据存储路径，获取照片真实URL
function get_photo_url($savepath) {
	//$path	=	'./data/uploads/' . $savepath;	if($savepath){
		$path	=	__ROOT__.'/data/uploads/' . $savepath;
	}else{
		$path = './apps/club/Tpl/desktop/Public/images/club_default.png';
	}
	return $path;
}//根据存储路径，获取照片真实URL
function get_photo_url_topic($savepath) {
	$path	=	'./data/uploads/' . $savepath;
	if(!file_exists($path))
		$path = './apps/club/Tpl/desktop/Public/images/default.jpg';
	return $path;
}//判断用户在该社团中是否是管理员function isManager( $uid='', $clubid='' ){	$map['clubid'] = $clubid;	$map['uid'] = $uid;	$findMember = M('club_member')->where($map)->field('id,type')->find();		if( !$findMember )		return false;	$type = $findMember['type'];	if( $type == 1 || $type == 2 ) {		return true;	} else {		return false;	}	}//判断用户是否是社团成员
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

}function formatsize($fileSize) {
	$size = sprintf("%u", $fileSize);
	if($size == 0) {
		return("0 Bytes");
	}
	$sizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
	return round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizename[$i];

}function returnMsg($msg,$status=0){
	$data['status']=$status;
	$data['msg']=$msg;
	echo json_encode($data);
	exit();
}
function getMemberCount(){	$map['type'] = array('in','1,2,3');
	$count = M('club_member')->where($map)->field('count(distinct uid) as membercount')->select();
	return $count[0]['membercount'];
}function getClubCount(){	$count = M('club')->field('count(id) as clubcount')->where('isdel=0')->select();	return $count[0]['clubcount'];}		