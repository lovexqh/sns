<?php
/**
 * Created by JetBrains PhpStorm.
 * User: 徐程亮
 * Date: 13-10-22
 * Time: 上午10:06
 * To change this template use File | Settings | File Templates.
 */

class ClubApi extends Api{

    function _initialize(){
    }

    private function get_photo_url($savepath) {
    	//$path	=	'/data/uploads/' . $savepath;
		//if(!file_exists('.'.$path))
			//$path = '/apps/club/Tpl/desktop/Public/images/club_pic.gif';
		if($savepath){
			$path	=	__ROOT__.'/data/uploads/' . $savepath;
		}else{
			$path = './apps/club/Tpl/desktop/Public/images/club_default.png';
		}
		return $path;
    }
    
    /**
     * @title 社团首页
     * @description  包括我的社团和新帖
     * @param  用户id, 每个社团新帖个数
     * @return  status, statusCode, data
     * @author	xiawei 2014-1-25
     */
    function getNewList(){
    	$uid = $_POST['uid'];
    	$count = $_POST['value'];
    	
    	$arrkey = array(
				'cid' => 'id',
				'cname' => 'title',
				'cmember' => 'membercount'
		);
		$data = D('ClubMember', 'club')->getClubByUid($uid);
		foreach($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			//dump($apiarr);exit;
			//头像路径
			$url = $val['logo'] = $this->get_photo_url($val['logo']);
			$apiarr[$key]['chead'] = SITE_URL.$url;
			//最新公告
			$noticeinfo = D('ClubNotice','club')->getLatestNotice($val['id']);
			$apiarr[$key]['cnotice'] = $noticeinfo['content'];
			//在社团中身份
			$memberInfo = D('ClubMember', 'club')->getMemberInfoInClub($val['id'], $uid);
			if( $memberInfo['type']==1 || $memberInfo['type']==2 ){
				$apiarr[$key]['ustatus'] = 1;
			}else{
				$apiarr[$key]['ustatus'] = 2;
			}
			//主题数
			$apiarr[$key]['tnumber'] = D('ClubTopic','club')->getTopicCountById($val['id']);
			//帖子
			$apiarr[$key]['tarray'] = D('ClubTopic','club')->getNewTopicByClubid($val['id'], $count);
			foreach ($apiarr[$key]['tarray'] as &$v){
				$v['uname'] = getUserName($v['uid']);
				$v['uhead'] = getUserFace($v['uid']);
				$v['ttime'] = date("Y-m-d H:i",$v['ttime']);
			}
				
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  获取社团列表,可根据社团类型,关键字搜索
     * @description 
     * @param  uid,type(社团类型,1学校社团,2学院社团,3学生社团),keyword(关键字)
     * @return
     * @author	xiawei 2014-1-26
     */
    function getlist(){
    	$uid = $_POST['uid'];
    	$type = $_POST['type'];
    	$keyword = $_POST['keyword'];
    	
    	$data = D('Club','club')->getClubByParam($type, $keyword);
    	foreach ($data as &$val){
    		$url = $this->get_photo_url($val['chead']);
    		$val['chead'] = SITE_URL.$url;
    		$memInfo = D('ClubMember','club')->getMemberInfoInClub($val['cid'], $uid);
    		if($memInfo['type'] == 1 || $memInfo['type'] == 2 || $memInfo['type'] == 3){
    			$val['join'] = 1;
    		}else if($memInfo['type']!=null && $memInfo['type'] == 0){
    			$val['join'] = 2;
    		}else{
    			$val['join'] = 0;
    		}
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $data;
    	return $list;
    }
    
    /**
     * @title  加入或退出社团
     * @description 
     * @param  用户id, 社团id, join(操作,1加入,2退出)
     * @return
     * @author	xiawei 2014-1-26
     */
    function doaction(){
    	$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	$join = $_POST['join'];
    	
    	$member = D( 'ClubMember','club' )->getMemberInfoInClub($cid, $uid);
    	if($join == 1){	//加入
    		$reason = $_POST['reason'];
    		if(!$reason){
    			$data['status'] = 2;
    			$data['statusCode'] = '申请理由不能为空';
    			return $data;
    		}
    		$map['reason'] = $reason;
    		if( $member ){
    			$map['type'] = 0;
    			$map['ctime'] = time();
    			$map['mtime'] = '';
    			$rs = D( 'ClubMember','club' )->updateMember($member['id'], $map);
    		}else{
    			$map['clubid'] = $cid;
    			$map['uid'] = $uid;
    			$map['username'] = getUserName($map['uid']);
    				
    			$ucUid = D( 'ClubMember','club' )->getUcUid($uid);
    			$userinfo = get_baseinfo_by_uid($ucUid);
    			if($userinfo['identitytype'] == 3){ //学生
    				$studentinfo = getStudentinfoByUid($ucUid);
    				$map['grade'] = $studentinfo[0]['nj'];
    			}else if($userinfo['identitytype'] == 2){	//老师
    				$map['grade'] = 1;
    			}
    			$map['type'] = 0;
    			$map['ctime'] = time();
    			$rs = D( 'ClubMember','club' )->addMember($map);
    		}
    	}else if($join == 2){	//退出
    		$map['type'] = 4;
    		$rs = D( 'ClubMember','club' )->chgMemberType($member['id'], $map);
    	}
    	$list['status'] = 0;
    	if($rs >= 0){
    		$list['statusCode'] = '操作成功!';
    	}else{
    		$list['statusCode'] = '操作失败!';
    	}
    	return $list;
    }
    
    /**
     * @title  获取社团基本信息以及该用户在社团中的权限
     * @description 
     * @param  用户id, 社团id
     * @return
     * @author	xiawei 2014-1-26
     */
    function detail(){
    	$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	
    	$arrkey = array(
    			'cname' => 'title',
    			'cmember' => 'membercount',
    			'cinfo' => 'description',
    			'pubtopic' => 'pubtopic',
    			'updoc' => 'updoc'
    	);
    	
    	$data = D('Club','club')->getClubById($cid);
    	$apiarr = getApiArray($data, $arrkey);
    	if($data['type']==1){
    		$apiarr['ctype'] = '学校社团';
    	}else if($data['type']==2){
    		$apiarr['ctype'] = '学院社团';
    	}else if($data['type']==3){
    		$apiarr['ctype'] = '学生社团';
    	}
    	$apiarr['cready'] = 0;
    	$memInfo = D('ClubMember','club')->getMemberInfoInClub($cid, $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$apiarr['ustatus'] = 1;
    		$applyMem = D('ClubMember','club')->getApplyMember($cid);
    		$apiarr['cready'] = count($applyMem);
    		$apiarr['cstatus'] = 1;
    	}else if($memInfo['type']==3){
    		$apiarr['ustatus'] = 2;
    		$apiarr['cstatus'] = 1;
    	}else if($memInfo['type']!=null && $memInfo['type'] == 0){
    		$apiarr['ustatus'] = 3;
    		$apiarr['cstatus'] = 2;
    	}else{
    		$apiarr['ustatus'] = 3;
    		$apiarr['cstatus'] = 0;
    	}
    	$url = $data['logo'] = $this->get_photo_url($data['logo']);
    	$apiarr['chead'] = SITE_URL.$url;
    	$noticeinfo = D('ClubNotice','club')->getLatestNotice($cid);
    	$apiarr['cnotice'] = $noticeinfo['content'];
    	if($data['pubtopic']==0){
    		if($memInfo['type']==1 || $memInfo['type']==2 || $memInfo['type']==3){
    			$apiarr['pubpost'] = 1;
    		}else{
    			$apiarr['pubpost'] = 0;
    		}
    	}else if($data['pubtopic']==1){
    		$apiarr['pubpost'] = 1;
    	}
    	if($data['updoc'] == 0){
    		if($memInfo['type']==1 || $memInfo['type']==2){
    			$apiarr['upload'] = 1;
    		}else{
    			$apiarr['upload'] = 0;
    		}
    	}else if($data['updoc'] == 1){
    		if($memInfo['type']==1 || $memInfo['type']==2 || $memInfo['type']==3){
    			$apiarr['upload'] = 1;
    		}else{
    			$apiarr['upload'] = 0;
    		}
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  编辑社团信息
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-26
     */
    function modifydetail(){
    	//$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	$map['description'] = $_POST['detail'];
    	$map['pubtopic'] = $_POST['pub'];
    	$map['updoc'] = $_POST['upload'];
    	
    	$rs = D('Club','club')->editClub($map, $cid);
    	$list['status'] = 0;
    	if($rs >= 0){
    		$list['statusCode'] = '修改成功!';
    	}else{
    		$list['statusCode'] = '修改失败!';    		
    	}
    	return $list;
    }
    
    /**
     * @title 上传社团头像(更新操作)
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-26
     */
    function uploadhead(){
    	//$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	
    	$options ['userId'] = $this->mid;
    	$options ['max_size'] = 2 * 1024 * 1024; // 2MB
    	$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
    	$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
    	if ($info ['status']) {
    		$map ['logo'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
    	} 
    	$rs = D('Club','club')->editClub($map, $cid);
    	$list['status'] = 0;
    	if($rs >= 0){
    		$list['statusCode'] = '修改成功!';
    	}else{
    		$list['statusCode'] = '修改失败!';    		
    	}
    	return $list;
    }
    
    /**
     * @title  获取指定社团帖子列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function tlist(){
    	$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	$type = $_POST['type'];
    	$top = $_POST['top'];
    	$supposed = $_POST['supposed'];
    	$page = $_POST['page'];
    	$num = $_POST['num'];
    	
    	$arrkey = array(
				'uid' => 'uid',
				'tid' => 'id',
				'thead' => 'topicpic',
				'ttitle' => 'title',
				'top' => 'zding',
				'supposed' => 'tjian',
				'reply' => 'replycount',
				'check' => 'clickcount'
		);
		$map['clubid'] = $cid;
		$map['type'] = 1;
		$map['isdel'] = 0;
		if( $type == 1 ){
			$map['uid'] = $uid;
		}
		if( $top == 1 ){
			$map['zding'] = 1;
		}
		if( $supposed == 1 ){
			$map['tjian'] = 1;
		}
		$start = ($page - 1) * $num;
		 
		$data = D('ClubTopic','club')->getTopicByParam($map, $start, $num);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
			$apiarr[$key]['tcontent'] = text($val['content']);
			$apiarr[$key]['ttime'] = date('Y-m-d H:i',$val['ctime']);
		}
		if(!$data){
			$list['status'] = 2;
			$list['statusCode'] = 'end';
		}else{
			$list['status'] = 0;
			$list['statusCode'] = '连接成功';
		}
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  对指定帖子进行置顶和推荐操作
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function optiont(){
    	//$uid = $_POST['uid'];
    	$tid = $_POST['tid'];
    	$top = $_POST['top'];
    	$suppose = $_POST['suppose'];
    	
    	$topicInfo = D('ClubTopic','club')->getTopicById($tid);
    	$statusCode = "";
    	$output['status'] = 0;
    	if(!$topicInfo || $topicInfo['isdel']==1){
    		$output['statusCode'] = '帖子不存在或已被删除';
    	}else{
    		if($top == 1){
    			$map['zding'] = 1;
    		}else if($top == 2){
    			$map['zding'] = 0;
    		}
    		if($suppose == 1){
    			$map['tjian'] = 1;
    		}else if($suppose == 2){
    			$map['tjian'] = 0;
    		}
    		$rs = D('ClubTopic','club')->operateTopic($map, $tid);
    		if($rs >= 0){
    			$output['statusCode'] = '操作成功';
    		}else{
    			$output['statusCode'] = '操作失败';
    		}
    	}
    	return $output;
    }
    
    /**
     * @title  删除指定帖子
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function delete(){
    	$tid = $_POST['tid'];
    	
    	$topicInfo = D('ClubTopic','club')->getTopicById($tid);
    	$statusCode = "";
    	$output['status'] = 0;
    	if(!$topicInfo || $topicInfo['isdel']==1){
    		$output['statusCode'] = '帖子不存在或已被删除';
    	}else{
    		$map['isdel'] = 1;
    		$rs = D('ClubTopic','club')->operateTopic($map, $tid);
    		if($rs >= 0){
    			$output['statusCode'] = '删除成功';
    		}else{
    			$output['statusCode'] = '删除失败';
    		}
    	}
    	return $output;
    }
    
    //发帖上传图片
    private function uploadPic(){
    	$options ['userId'] = $this->mid;
    	$options ['max_size'] = 100 * 1024 * 1024; // 100MB
    	$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
    	$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
    	if ($info ['status']) {
    		$url = SITE_URL.'/data/uploads/'.$info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
    		$data['status'] = 1;
    		$data['url'] = $url;
    	}else{
    		$data['status'] = 0;
    		$data['statusCode'] = '上传图片失败';
    	}
    	return $data;
    }
    
    /**
     * @title  发布帖子
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function postt(){
    	$uploadRs = '';
    	if($_FILES['pic']){
    		$uploadRs = $this->uploadPic();
    	}
    	
    	$uid = $_POST['uid'];
    	$cid = $_POST['cid'];
    	$ttitle = trim($_POST['ttitle']);
    	$tcontent = trim($_POST['tcontent']);
    	$ttype = $_POST['ttype'];
    	
    	$map['clubid'] = $cid;
    	$map['uid'] = $uid;
    	$map['title'] = $ttitle;
    	$map['type'] = 1;
    	$map['content'] = $tcontent;
    	$map['ctime'] = time();
    	if($ttype == 1){
    		$map['replyman'] = 0;
    	}else if($ttype == 2){
    		$map['replyman'] = 1;
    	}
    	if($uploadRs){
    		if($uploadRs['status']==1){
    			$map['content'] = $map['content'].'<br /><img style="float:none;margin:0px;" alt="" src="'.$uploadRs['url'].'" />';
    			$map['topicpic'] = $uploadRs['url'];
    		}
    	}
    	$rs = D('ClubTopic','club')->addTopic($map);
    	$list['status'] = 0;
    	if($rs){
    		$list['statusCode'] = '发布成功';
    	}else{
    		$list['statusCode'] = '发布失败';
    	}
    	return $list;
    }
    
    /**
     * @title  获取指定帖子详情
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function tdetail(){
    	$uid = $_POST['uid'];
    	$tid = $_POST['tid'];
    	
    	$arrkey = array(
				'uid' => 'uid',
				'tid' => 'id',
				'ttitle' => 'title',
				'tcontent' => 'content',
				'reply' => 'replycount',
				'check' => 'clickcount'
		);
		$data = D( 'ClubTopic','club' )->getTopicById($tid);
		$apiarr = getApiArray($data, $arrkey);
		$apiarr['uname'] = getUserName($data['uid']);
		$apiarr['uhead'] = getUserFace($data['uid']);
		//$data['content'] = $data['content'];
		$apiarr['ttime'] = date("Y-m-d H:i",$data['ctime']);
		if($data['replyman'] == 1){
			$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($data['clubid'], $uid);
			if($memInfo['type']==1 || $memInfo['type']==2 || $memInfo['type']==3){
				$apiarr['edit'] = 1;
			}else{
				$apiarr['edit'] = 0;
			}
		}else{
			$apiarr['edit'] = 1;
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  根据帖子id获取评论列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function replylist(){
    	//$uid = $_POST['uid'];
    	$tid = $_POST['tid'];
    	$page = $_POST['page'];
    	$num = $_POST['num'];
    	
    	$arrkey = array(
    			'rid' => 'id',
    			'rcontext' => 'content',
    			'uid' => 'uid'
    	);
    	$arrkey1 = array(
    			'rrid' => 'id',
    			'rrcontext' => 'content',
    			'uid' => 'uid'
    	);
    	$start = ($page - 1) * $num;
    	$data = D( 'ClubReply','club' )->getReplyForMobile($tid,$start,$num);
    	foreach ($data as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    		$apiarr[$key]['uname'] = getUserName($val['uid']);
    		$apiarr[$key]['uhead'] = getUserFace($val['uid'],'s');
    		$apiarr[$key]['rdate'] = date("Y-m-d H:i",$val['ctime']);
    		$rrlist = D( 'ClubReply','club' )->getReplyByReplyid( $val['id'] );
    		$apiarr[$key]['rrnumber'] = count($rrlist);
    		//$apiarr[$key]['rrlist'] = $rrlist;
    		foreach ($rrlist as $k => $v){
    			$apiarr1[$key][$k] = getApiArray($v, $arrkey1);
    			$apiarr1[$key][$k]['uname'] = getUserName($v['uid']);
    			$apiarr1[$key][$k]['uhead'] = getUserFace($v['uid']);
    			$apiarr1[$key][$k]['rrdate'] = date("Y-m-d H:i",$v['ctime']);
    		}
    		$apiarr[$key]['rrlist'] = $apiarr1[$key];
    	}
    	if(!$data){
    		$list['status'] = 2;
    		$list['statusCode'] = 'end';
    	}else{
    		$list['status'] = 0;
    		$list['statusCode'] = '连接成功';
    	}
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  发布评论.发布帖子的评论，或者发布评论的回复
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function reply(){
    	$uid = $_POST['uid'];
    	$tid = $_POST['tid'];
    	$rid = $_POST['rid'];
    	$rcontent = $_POST['rcontent'];
    	
    	$map['topicid'] = $tid;
    	$map['uid'] = $uid;
    	$map['content'] = htmlspecialchars($rcontent);
    	$map['ctime'] = time();
    	if(!$rid){
    		$map['replyid'] = 0;
    		$topicInfo = D('ClubTopic','club')->getTopicById($tid);
    		$map['replyuid'] = $topicInfo['uid'];
    		$maxFloor = D( 'ClubReply','club' )->getMaxFloor($tid);
    		if( $maxFloor == null ){
    			$map['floor'] = 2;
    		}else{
    			$map['floor'] = $maxFloor + 1;
    		}
    	}else{
    		$map['replyid'] = $rid;
    		$replyInfo = D('ClubReply','club')->getReplyById($rid);
    		$map['replyuid'] = $replyInfo['uid'];
    	}
    	$rs = D('ClubReply','club')->addReply( $map );
    	$list['status'] = 0;
    	if($rs){
    		if($map['replyid'] == 0){
    			$data['replytime'] = time();
    			$data['replyuid'] = $uid;
    			D( 'ClubTopic','club' )->updateReplyInfoInTopic( $data, $tid );
    		}
    		$list['statusCode'] = '发布成功';
    	}else{
    		$list['statusCode'] = '发布失败';
    	}
    	return $list;
    }
    
    /**
     * @title  获取指定社团的风采列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-1-27
     */
    function mienlist(){
    	$cid = $_POST['cid'];
    	
    	$arrkey = array(
				'uid' => 'uid',
				'mid' => 'id',
				'mhead' => 'topicpic',
				'mreply' => 'replycount',
				'mtitle' => 'title',
				'mcontent' => 'content',
				'mperiod' => 'issue'
		);
		$data = D('ClubTopic','club')->getAllEvent($cid);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
			$apiarr[$key]['mdate'] = date("Y-m-d H:i",$val['ctime']);
			$apiarr[$key]['mcontent'] = text($apiarr[$key]['mcontent']);
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  获取指定风采的详情
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function mdetail(){
    	$mid = $_POST['mid'];
    	$uid = $_POST['uid'];
    	
    	$arrkey = array(
    			'uid' => 'uid',
    			'tid' => 'id',
    			'ttitle' => 'title',
    			'tcontent' => 'content',
    			'reply' => 'replycount'
    	);
    	$data = D('ClubTopic','club')->getTopicById($mid);
    	$apiarr = getApiArray($data, $arrkey);
    	$apiarr['uname'] = getUserName($data['uid']);
    	$apiarr['uhead'] = getUserFace($data['uid']);
    	$apiarr['ttime'] = date("Y-m-d H:i",$data['ctime']);
    	 
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($data['clubid'], $uid);
    	if($data['replyman'] == 1){
    		if($memInfo['type']==1 || $memInfo['type']==2 || $memInfo['type']==3){
    			$apiarr['edit'] = 1;
    		}else{
    			$apiarr['edit'] = 0;
    		}
    	}else{
    		$apiarr['edit'] = 1;
    	}
    	$apiarr['delete'] = 0;
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$apiarr['delete'] = 1;
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  获取指定风采获取评论列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function mienreplylist(){
    	$mid = $_POST['mid'];
    	$page = $_POST['page'];
    	$num = $_POST['num'];
    	
    	$arrkey = array(
				'rid' => 'id',
				'rcontext' => 'content',
				'uid' => 'uid'
		);
		$start = ($page - 1) * $num;
		$data = D( 'ClubReply','club' )->getReplyForMobile($mid,$start,$num);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid'],'s');
			$apiarr[$key]['rdate'] = date("Y-m-d H:i",$val['ctime']);
		}
		if(!$data){
			$list['status'] = 2;
			$list['statusCode'] = 'end';
		}else{
			$list['status'] = 0;
			$list['statusCode'] = '连接成功';
		}
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  删除指定风采
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function deletemien(){
    	$uid = $_POST['uid'];
    	$mid = $_POST['mid'];
    	
    	$detail = D('ClubTopic','club')->getTopicById($mid);
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($detail['clubid'], $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$map['isdel'] = 1;
    		$delrs = D( 'ClubTopic','club' )->operateTopic($map, $mid);
    		if($delrs){
    			$data['status'] = 0;
    			$data['statusCode'] = '删除成功';
    		}else{
    			$data['status'] = 2;
    			$data['statusCode'] = '删除失败';
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有删除权限';
    	}
    	return $data;
    }
    
    /**
     * @title  发布风采
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function postmien(){
    	$map['clubid'] = $_POST['cid'];
    	$map['uid'] = $_POST['uid'];
    	$map['title'] = $_POST['mtitle'];
    	$map['content'] = $_POST['mcontent'];
    	//$map['issue'] = $_POST['period'];
    	$mtype = $_POST['mtype'];
    	if($mtype == 1){
    		$map['replyman'] = 0;
    	}else if($mtype == 2){
    		$map['replyman'] = 1;
    	}
    	$map['type'] = 2;
    	$map['ctime'] = time();
    	$maxIssue = D('ClubTopic','club')->getMaxIssue( $map['clubid'] );
    	$map['issue'] = intval($maxIssue) + 1;
    	$rs = D('ClubTopic','club')->addTopic($map);
    	if($rs){
    		$data['status'] = 0;
    		$data['statusCode'] = '发布成功';
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '发布失败';
    	}
    	return $data;
    }
    
    /**
     * @title  发布风采评论
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function mienreply(){
    	$map['topicid'] = $_POST['mid'];
    	$map['uid'] = $_POST['uid'];
    	$map['content'] = $_POST['rcontent'];
    	$map['replyid'] = 0;
    	
    	$topicInfo = D('ClubTopic','club')->getTopicById($map['topicid']);
    	if( $topicInfo['replyman'] == 1 ){
    		$memberinfo = D('ClubMember', 'club')->getMemberInfoInClub($topicInfo['clubid'], $map['uid']);
    		if( !in_array($memberinfo['type'], array(1,2,3)) ){
    			$data['status'] = 2;
    			$data['statusCode'] = '非社团成员不能评论';
    			return $data;
    		}
    	}
    	$map['replyuid'] = $topicInfo['uid'];
    	$map['ctime'] = time();
    	$rs = D('ClubReply','club')->addReply( $map );
    	if($rs){
    		$data['status'] = 0;
    		$data['statusCode'] = '发布成功';
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '发布失败';
    	}
    	return $data;
    }
    
    /**
     * @title  获取指定社团的文档列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function resourcelist(){
    	$cid = $_POST['cid'];
    	$page = $_POST['page'];
    	$num = $_POST['num'];
    	
    	$arrkey = array(
				'sid' => 'id',
				'stitle' => 'title',
				'ssize' => 'filesize',
				'stime' => 'downcount',
				'sdate' => 'ctime',
				'uid' => 'uid'
		);
		$start = ($page - 1) * $num;
		$data = D( 'ClubDocument','club' )->getDocListForMobile($cid,$start,$num);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$config = model('Xdata')->lget('resource');
			$apiarr[$key]['spath'] = $config['server'].$val['savepath'].$val['savename'];
			$apiarr[$key]['stype'] = SITE_URL."/apps/club/Tpl/desktop/Public/images/icon/".$val['filetype'].".gif";
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['num'] = D('ClubDocument','club')->getFileCount($cid);
		$list['size'] = D('ClubDocument','club')->getUsedSpace($cid);
		$list['data'] = $apiarr;
		return $list;
    }
    
    /**
     * @title  获取指定社团的公告列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function noticelist(){
    	$cid = $_POST['cid'];
    	
    	$arrkey = array(
    			'uid' => 'uid',
    			'nid' => 'id',
    			'ncontent' => 'content'
    	);
    	$data = D('ClubNotice','club')->getAllNotice($cid);
    	foreach ($data as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    		$apiarr[$key]['uname'] = getUserName($val['uid']);
    		$apiarr[$key]['uhead'] = getUserFace($val['uid']);
    		$apiarr[$key]['ndate'] = date("Y-m-d H:i",$val['ctime']);
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  发布指定社团公告
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function pubnotice(){
    	$map['clubid'] = $_POST['cid'];
    	$map['uid'] = $_POST['uid'];
    	$map['content'] = $_POST['ncontent'];
    	$map['ctime'] = time();
    	
    	$rs = D('ClubNotice','club')->addNotice($map);
    	if($rs){
    		$data['status'] = 0;
    		$data['statusCode'] = '发布成功';
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '发布失败';
    	}
    	return $data;
    }
    
    /**
     * @title  删除指定社团的公告
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function deletenotice(){
    	$nid = $_POST['nid'];
    	$uid = $_POST['uid'];
    	
    	$detail = D('ClubNotice','club')->getNoticeById($nid);
    	if(!$detail){
    		$data['status'] = 2;
    		$data['statusCode'] = '公告不存在或已被删除';
    	}else{
    		$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($detail['clubid'], $uid);
    		if($memInfo['type']==1 || $memInfo['type']==2){
    			$map['isdel'] = 1;
    			$delrs = D( 'ClubNotice','club' )->deleteNotice($map, $nid);
    			if($delrs){
    				$data['status'] = 0;
    				$data['statusCode'] = '删除成功';
    			}else{
    				$data['status'] = 2;
    				$data['statusCode'] = '删除失败';
    			}
    		}else{
    			$data['status'] = 2;
    			$data['statusCode'] = '没有删除权限';
    		}
    	}
    	return $data;
    }
    
    /**
     * @title  获取指定社团的财务列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function finlist(){
    	$sdate = $_POST['sdate'];
    	$edate = $_POST['edate'];
    	$map['clubid'] = $_POST['cid'];
    	$page = $_POST['page'];
    	$num = $_POST['num'];
    	$arrkey = array(
    			'fid' => 'id',
    			'fname' => 'title',
    			'fsum' => 'totalmoney'
    	);
    	if($sdate && $edate){
    		$map['addtime'] = array('between', array($sdate, $edate));
    	}
    	 
    	$start = ($page - 1) * $num;
    	$data = D('ClubAccount','club')->getAccountForMobile($map,$start,$num);
    	foreach ($data as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    		$apiarr[$key]['fdate'] = date("Y-m-d H:i",$val['addtime']);
    		if($val['type']==1){
    			$apiarr[$key]['ftype'] = '收入';
    		}else if($val['type']==2){
    			$apiarr[$key]['ftype'] = '支出';
    		}
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$balance = D('Club','club')->getClubBalanceById($map['clubid']);
    	$list['fsum'] = $balance['balance'];
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  发布指定社团财务
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function pubfin(){
    	$map['adduid'] = $_POST['uid'];
    	$map['clubid'] = $_POST['cid'];
    	$map['title'] = $_POST['fname'];
    	$map['addtime'] = $_POST['fdate'];
    	$ftype = $_POST['ftype'];
    	if($ftype == 1){
    		$map['type'] = 1;
    	}else if($ftype == 0){
    		$map['type'] = 2;
    	}
    	$rs = D('ClubAccount','club')->addAccount($map);
    	if($rs){
    		$data['status'] = 0;
    		$data['statusCode'] = '发布成功';
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '发布失败';
    	}
    	return $data;
    }
    
    /**
     * @title  删除指定ID的财务
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function deletefinance(){
    	$fid = $_POST['fid'];
    	$uid = $_POST['uid'];
    	$detail = D('ClubAccount','club')->getAccountById($fid);
    	if(!$detail){
    		$data['status'] = 2;
    		$data['statusCode'] = '财务不存在';
    	}else if($detail['isdel']==1){
    		$data['status'] = 2;
    		$data['statusCode'] = '财务已被删除';
    	}else{
    		$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($detail['clubid'], $uid);
    		if($memInfo['type']==1 || $memInfo['type']==2){
    			$map['isdel'] = 1;
    			$delrs = D( 'ClubAccount','club' )->delAccount($fid, $uid);
    			if($delrs){
    				//删除名目下款项的信息
    				$delItemRs = D( 'ClubAccountItem','club' )->delItemByAccountid($fid, $uid);
    				//更新余额
    				$accountInfo = D( 'ClubAccount','club' )->getAccountById( $fid );
    				$updateBalance = D( 'Club','club' )->updateDelBalance($accountInfo['clubid'], $accountInfo['totalmoney'], $accountInfo['type']);
    				$data['status'] = 0;
    				$data['statusCode'] = '删除成功';
    			}else{
    				$data['status'] = 2;
    				$data['statusCode'] = '删除失败';
    			}
    		}else{
    			$data['status'] = 2;
    			$data['statusCode'] = '没有删除权限';
    		}
    	}
    	return $data;
    }
    
    /**
     * @title  获取指定社团指定财务名目下的款项列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function fundlist(){
    	$fid = $_POST['fid'];
    	$arrkey = array(
    			'fundid' => 'id',
    			'funame' => 'title',
    			'fuin' => 'money',
    			'fdis' => 'remark',
    			'foprator' => 'useperson'
    	);
    	$data = D('ClubAccountItem','club')->getAccountItem( $fid );
    	foreach ($data as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  发布指定社团下，指定名目下的款项
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function pubfund(){
    	$accountid = $_POST['fid'];
    	$account = D('ClubAccount','club')->getAccountById($accountid);
    	$map['clubid'] = $account['clubid'];
    	$map['accountid'] = $accountid;
    	$map['title'] = $_POST['funame'];
    	$map['money'] = $_POST['fuin'];
    	$map['remark'] = $_POST['fdis'];
    	$map['useperson'] = $_POST['foprator'];
    	$map['addtime'] = time();
    	$map['adduid'] = $_POST['uid'];
    	$addRs = D( 'ClubAccountItem','club' )->addAccountItem($map);
    	if($addRs){
    		//更新收入或支出总金额
    		$updateAccRs = D( 'ClubAccount','club' )->updateAddMoney($accountid, $map['money'], $map['adduid']);
    		//更新余额
    		$accountInfo = D( 'ClubAccount','club' )->getAccountById( $accountid );
    		$type = $accountInfo['type'];
    		$updateBalance = D( 'Club','club' )->updateAddBalance($map['clubid'], $map['money'], $type);
    		$data['status'] = 0;
    		$data['statusCode'] = '发布成功';
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '发布失败';
    	}
    	return $data;
    }
    
    /**
     * @title  删除指定款项
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function deletefund(){
    	$uid = $_POST['uid'];
    	$itemid = $_POST['fundid'];
    	$accountItem = D('ClubAccountItem','club')->getAccountItemById($itemid);
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($accountItem['clubid'], $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$accountid = $accountItem['accountid'];
    		$itemInfo = D( 'ClubAccountItem','club' )->getAccountItemById( $itemid );
    		$accountInfo = D( 'ClubAccount','club' )->getAccountById( $accountid );
    		$clubid = $accountInfo['clubid'];
    		$money = $itemInfo['money'];
    		$type = $accountInfo['type'];
    		//删除该条款项
    		$delItemRs = D( 'ClubAccountItem','club' )->delAccountItem($itemid, $uid);
    		 
    		if($delItemRs){
    			//更新名目的总计金额
    			$updateMoneyRs = D( 'ClubAccount','club' )->updateDelMoney($accountid, $money, $uid);
    			//更新余额
    			$updateBalance = D( 'Club', 'club' )->updateDelBalance($clubid, $money, $type);
    			$data['status'] = 0;
    			$data['statusCode'] = '删除成功';
    		}else{
    			$data['status'] = 2;
    			$data['statusCode'] = '删除失败';
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有删除权限';
    	}
    	return $data;
    }
    
    /**
     * @title  获取指定社团的部门列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function department(){
    	$cid = $_POST['cid'];
    	$arrkey = array(
    			'did' => 'id',
    			'dname' => 'deptname'
    	);
    	$data = D( 'ClubDept','club' )->getDeptByClubid($cid);
    	foreach ($data as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  修改，增加部门
     * @description  指定部门id，如果传的话代表修改，如果不传代表新添
     * @param
     * @return
     * @author	xiawei 2014-2-7
     */
    function modifydepartment(){
    	$cid = $_POST['cid'];
    	$uid = $_POST['uid'];
    	$dname = $_POST['dname'];
    	$did = $_POST['did'];
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($cid, $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$findDept = D( 'ClubDept','club' )->chkDeptName($cid, $dname, $did);
    		if($findDept){
    			$data['status'] = 2;
    			$data['statusCode'] = '部门名已存在';
    		}else{
    			if($did){	//修改
    				$map['deptname'] = $dname;
    				$map['inputuid'] = $uid;
    				$updateRs = D( 'ClubDept','club' )->updateDept( $did, $map );
    				if($updateRs>=0){
    					$data['status'] = 0;
    					$data['statusCode'] = '更新成功';
    				}else{
    					$data['status'] = 0;
    					$data['statusCode'] = '更新失败';
    				}
    			}else{	//添加
    				$deptInClub = D( 'ClubDept','club' )->getDeptByClubid( $cid );
    				$deptCount = count($deptInClub);
    				if($deptCount > 9){
    					$data['status'] = 2;
    					$data['statusCode'] = '不能超过10个部门';
    				}else{
    					$map['clubid'] = $cid;
    					$map['deptname'] = $dname;
    					$map['inputuid'] = $uid;
    					$addRs = D( 'ClubDept','club' )->addDept( $map );
    					if($addRs){
    						$data['status'] = 0;
    						$data['statusCode'] = '添加成功';
    					}else{
    						$data['status'] = 2;
    						$data['statusCode'] = '添加失败';
    					}
    				}
    			}
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有相关权限';
    	}
    	return $data;
    }
    
    /**
     * @title  删除部门
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-8
     */
    function deletedepartment(){
    	$uid = $_POST['uid'];
    	$did = $_POST['did'];
    	
    	$detail = D('ClubDept','club')->getDeptById($did);
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($detail['clubid'], $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		if(!$detail || $detail['isdel']==1){
    			$data['status'] = 2;
    			$data['statusCode'] = '部门不存在或已被删除';
    		}else{
    			$map['isdel'] = 1;
    			$delrs = D( 'ClubDept','club' )->delDept($did);
    			if($delrs){
    				$data['status'] = 0;
    				$data['statusCode'] = '删除成功';
    			}else{
    				$data['status'] = 2;
    				$data['statusCode'] = '删除失败';
    			}
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有删除权限';
    	}
    	
    	return $data;
    }
    
    /**
     * @title  获取指定社团成员列表；包括全部成员。包括待审核成员，包括已退出成员。
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-8
     */
    function member(){
    	$arrkey = array(
    			'uid' => 'id',
    			'uclass' => 'grade',
    			'reason' => 'reason'
    	);
    	
    	$cid = $_POST['cid'];
    	$ctype = $_POST['ctype'];
    	
    	if($ctype==0){
    		$map['type'] = array('in','1,2,3');
    		$did = $_POST['did'];
    		if($did){
    			$map['deptid'] = $did;
    		}
    	}else if($ctype==1){
    		$map['type'] = 0;
    	}else if($ctype==2){
    		$map['type'] = 4;
    	}
    	$map['clubid'] = $cid;
    	$memList = D('ClubMember','club')->getMemberByParam($map);
    	foreach ($memList as $key => $val){
    		$apiarr[$key] = getApiArray($val, $arrkey);
    		$apiarr[$key]['uname'] = getUserName($val['uid']);
    		$apiarr[$key]['uhead'] = getUserFace($val['uid']);
    		$apiarr[$key]['udate'] = date("Y-m-d H:i",$val['ctime']);
    		$uchara = '';
    		if($val['type']==1){
    			$uchara = '创建者';
    		}else if($val['type']==2){
    			$uchara = '管理员';
    		}else if($val['type']==3){
    			$uchara = '成员';
    		}
    		$apiarr[$key]['uchara'] = $uchara;
    		if(!empty($apiarr[$key]['uclass']) && $apiarr[$key]['uclass']!=1){
    			$apiarr[$key]['uclass'] = $apiarr[$key]['uclass'].'级';
    		}else if($apiarr[$key]['uclass'] == 1){
    			$apiarr[$key]['uclass'] = '(老师)';
    		}else{
    			$apiarr[$key]['uclass'] = '--';
    		}
    	}
    	$list['status'] = 0;
    	$list['statusCode'] = '连接成功';
    	$list['data'] = $apiarr;
    	return $list;
    }
    
    /**
     * @title  指定成员进行操作，包括设为管理员，取消管理员，换届，踢出。审核，审核拒绝
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-8
     */
    function memberopration(){
    	$cid = $_POST['cid'];
    	$uid = $_POST['uid'];
    	$mid = $_POST['mid'];
    	$operation = $_POST['operation'];
    	
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($cid, $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		if($operation == 1){
    			$map['type'] = 2;
    		}else if($operation == 2){
    			$map['type'] = 3;
    		}else if($operation == 3){
    			$map['type'] = 4;
    		}else if($operation == 4){
    			$map['type'] = 5;
    		}else if($operation == 5){
    			$map['type'] = 3;
    			$map['mtime'] = time();
    		}else if($operation == 6){
    			$map['type'] = 6;
    		}
    		$meminfo = D( 'ClubMember','club' )->getMemberById($mid);
    		if( in_array($map['type'], array(3,4,5)) ){
    			$managerList = D( 'ClubMember','club' )->getManagerByClubid( $cid );
    			if( count($managerList) == 1 && $meminfo['uid'] == $memInfo['uid'] ){
    				$data['status'] = 2;
    				$data['statusCode'] = '社团中至少一个管理员';
    				return $data;
    			}
    		}
    		$rs = D('ClubMember','club')->chgMemberType($mid,$map);
    		if($rs >= 0){
    			if( $rs > 0 ){
    				if( $map['type']==4 || $map['type']==5 || $operation == 5 ){
    					$map1['membercount'] = D( 'ClubMember','club' )->getMemberCount($cid);
    					D( 'Club', 'club' )->updateClub($cid, $map1);
    				}
    			}
    			$data['status'] = 0;
    			$data['statusCode'] = '操作成功';
    		}else{
    			$data['status'] = 2;
    			$data['statusCode'] = '操作失败';
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有操作权限';
    	}
    		
    	return $data;
    }
    
    /**
     * @title  指定成员进行部门修改
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-9
     */
    function membedepartment(){
    	$cid = $_POST['cid'];
    	$uid = $_POST['uid'];
    	$mid = $_POST['mid'];
    	$did = $_POST['did'];
    	
    	$memInfo = D('ClubMember', 'club')->getMemberInfoInClub($cid, $uid);
    	if($memInfo['type']==1 || $memInfo['type']==2){
    		$deptinfo = D('ClubDept','club')->getDeptById($did);
    		if(!$deptinfo || $deptinfo['clubid'] != $cid || $deptinfo['isdel'] == 1){
    			$data['status'] = 2;
    			$data['statusCode'] = '部门不存在';
    		}else{
    			$map['deptid'] = $did;
    			$rs = D('ClubMember','club')->chgMemberDept($map, $mid);
    			if($rs >= 0){
    				$data['status'] = 0;
    				$data['statusCode'] = '操作成功';
    			}else{
    				$data['status'] = 2;
    				$data['statusCode'] = '操作失败';
    			}
    		}
    	}else{
    		$data['status'] = 2;
    		$data['statusCode'] = '没有操作权限';
    	}
    	
    	return $data;
    }
    
    /**
     * @title  获取指定社团没课成员列表
     * @description 
     * @param
     * @return
     * @author	xiawei 2014-2-9
     */
    function noclassmember(){
    	$cid = $_POST['cid'];
    	$date = $_POST['date'];
    	$lessons = $_POST['lessons'];
    	
    	$arrkey = array(
    			'uid' => 'id',
    			'uclass' => 'grade'
    	);
    	$searchtime = date("Y-m-d",$date);
    	//第几周
    	$week = D('Course','teaching')->datetoweek( $searchtime );
    	if($week > 24 || $week < 1){
    		$data['status'] = 2;
    		$data['statusCode'] = '日期非本学期';
    	}else{
    		if( $searchtime && $lessons ){
    			$haveClassInfo = $this->findHaveClassMember($cid, $searchtime, $lessons, $week);
    			foreach ($haveClassInfo as $val){
    				$uid = M('ucenter_user_link')->where('uc_uid = '.$val)->getField('uid');
    				$haveClassUid[] = $uid;
    			}
    			$memList = D( 'ClubMember','club' )->getMemberInfoByClubid( $cid );
    			foreach ($memList as $v){
    				if( !in_array($v['uid'], $haveClassUid) ){
    					$noClassMember[] = $v;
    				}
    			}
    			foreach ($noClassMember as $key => $value){
    				$apiarr[$key] = getApiArray($value, $arrkey);
    				$apiarr[$key]['uname'] = getUserName($value['uid']);
    				$apiarr[$key]['uhead'] = getUserFace($value['uid']);
    				$apiarr[$key]['udate'] = date("Y-m-d H:i",$value['ctime']);
    				if( $value['type'] == 1 ){
    					$apiarr[$key]['uchara'] = '创建者';
    				}else if ( $value['type'] == 2 ){
    					$apiarr[$key]['uchara'] = '管理员';
    				}else if ( $value['type'] == 3 ){
    					$apiarr[$key]['uchara'] = '成员';
    				}
    				if(!empty($apiarr[$key]['uclass']) && $apiarr[$key]['uclass']!=1){
    					$apiarr[$key]['uclass'] = $apiarr[$key]['uclass'].'级';
    				}else if($apiarr[$key]['uclass'] == 1){
    					$apiarr[$key]['uclass'] = '(老师)';
    				}else{
    					$apiarr[$key]['uclass'] = '--';
    				}
    			}
    			$data['status'] = 0;
    			$data['statusCode'] = '连接成功';
    			$data['data'] = $apiarr;
    		}else if( !$searchtime && $lessons ){
    			$data['status'] = 2;
    			$data['statusCode'] = '日期为空';
    		}else if( $searchtime && !$lessons ){
    			$data['status'] = 2;
    			$data['statusCode'] = '上课节次为空';
    		}
    	}
    	return $data;
    }
    
    //根据日期,节次查找没课的成员
    private function findHaveClassMember($clubid, $searchtime, $classno, $week){
    	//社团成员uid,字符串形式
    	$uidList = D( 'ClubMember','club' )->getMemberInfoByClubid( $clubid );
    	$stustr = "";
    	$teastr = "";
    	foreach ($uidList as $value){
    		$ucUid = M('ucenter_user_link')->where('uid = '.$value['uid'])->getField('uc_uid');
    		$userinfo = get_baseinfo_by_uid($ucUid);
    		if( $userinfo['identitytype'] == 2 ){
    			$teastr .= $ucUid.',';
    		}else if( $userinfo['identitytype'] == 3 ){
    			$stustr .= $ucUid.',';
    		}
    	}
    	
    	//星期几
    	$weeknum = date("w", strtotime($searchtime));
    	//获取的字段
    	//$columns = 'uid, member.identityID, member.identityType';
    
    	if($teastr){
    		$teastr = substr($teastr,0,(strlen($teastr)-1));
    		$haveClassTeacherUids = getHaveClassTeacherUids($teastr, $week, $weeknum, $classno);
    	}else{
    		$haveClassTeacherUids = "";
    	}
    	if($stustr){
    		$stustr = substr($stustr,0,(strlen($stustr)-1));
    		$haveClassUids = getHaveClassUids($stustr, $week, $weeknum, $classno);
    	}else{
    		$haveClassUids = "";
    	}
    
    	$uids = array();
    	foreach ($haveClassTeacherUids as $val){
    		$uids[] = $val['uid'];
    	}
    	foreach ($haveClassUids as $v){
    		$uids[] = $v['uid'];
    	}
    	//$uids = array_merge($haveClassUids, $haveClassTeacherUids);
    	//dump($haveClassTeacherUids);exit;
    
    	return $uids;
    }
    
}