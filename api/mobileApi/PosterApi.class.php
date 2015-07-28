<?php
class PosterApi extends Api{
	/**
	 * @title  获取招贴中的所有系统分类及当前分类下的子分类
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-9
	 */
	public function getPstList(){
		$pid = $_POST['pid'];
		
		if($pid){
			$typeInfo = D('PosterType','poster')->getTypeById($pid);
			$data = D('PosterSmallType','poster')->getPosterSmallType($typeInfo['type']);
			foreach ($data as &$val){
				$val['ico'] = '';
			}
		}else{
			$data = D('PosterType','poster')->getAllType();
			foreach ($data as &$val){
				$val['ico'] = SITE_URL.'/apps/poster/Tpl/desktop/Public/images/ico/'.$val['ico'];
			}
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $data;
		return $list;
	}
	
	/**
	 * @title  查看所有招贴的列表及我关注的人的招贴及对应分类下的招贴信息
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-9
	 */
	function getAllList(){
		$uid = $_POST['uid'];
		$pid = $_POST['pid'];
		$stid = $_POST['stid'];
		$keyword = $_POST['keyword'];
		$useful = $_POST['useful'];
		$page = $_POST['page'];
		$value = $_POST['value'];
		
		$arrkey = array(
				'uid' => 'uid',
				'title' => 'title',
				'postid' => 'id',
				'content' => 'content'
		);
		
		if($uid){
			$map['uid'] = $uid;
		}
		if($pid){
			$map['pid'] = $pid;
			$typeInfo = D('PosterType','poster')->getTypeById($pid);
			if($typeInfo){
				if($stid){
					$map['type'] = $stid;
				}else{
					$smallType = D('PosterSmallType','poster')->getPosterSmallType($typeInfo['type']);
					if($smallType){
						foreach ($smallType as $val){
							$ids[] = $val['id'];
						}
						$map['type'] = array('in',$ids);
					}
				}
			}else{
				$list['status'] = 2;
				$list['statusCode'] = '大分类错误';
				$list['data'] = array();
				return $list;
			}
		}
		
		if($keyword != ''){
			$map['title'] = array( 'like','%'.$keyword.'%' );
		}
		if($useful == 1){
			$map['deadline'] = array('EGT', time());
		}else if($useful == 2){
			$map['deadline'] = array('LT', time());
		}
		$data = D('Poster','poster')->getPosterByParam($map,$page,$value);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
			if($val['deadline'] >= time()){
				$apiarr[$key]['useful'] = 1;
			}else{
				$apiarr[$key]['useful'] = 2;
			}
			$apiarr[$key]['time'] = date("Y-m-d H:i",$val['cTime']);
			$apiarr[$key]['stname'] = D('PosterSmallType','poster')->getTypeName($val['type']);
			$apiarr[$key]['pname'] = D('PosterType','poster')->getTypeName($val['pid']);
			if($val['cover']){
				$apiarr[$key]['contentimg'] = SITE_URL.'/data/uploads/'.$val['cover'];
			}else{
				$apiarr[$key]['contentimg'] = SITE_URL.'/apps/poster/Tpl/desktop/Public/images/default_post.gif';
			}
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
	}
	
	/**
	 * @title  查看我的招贴及我收藏的招贴
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-9
	 */
	function getUserList(){
		$uid = $_POST['uid'];
		$type = $_POST['type'];
		$useful = $_POST['useful'];
		$page = $_POST['page'];
		$pagesize = $_POST['pagesize'];
		
		$arrkey = array(
				'uid' => 'uid',
				'title' => 'title',
				'postid' => 'id',
				'content' => 'content',
		);
		if($type == 1){
			$data = $this->getMyPoster($uid, $useful, $page, $pagesize);
		}else if($type == 2){
			$data = $this->getMyFavPoster($uid, $useful, $page, $pagesize);
		}else if(!$type){
			$myPoster = $this->getMyPoster($uid, $useful, $page, $pagesize);
			$myFavPoster = $this->getMyFavPoster($uid, $useful, $page, $pagesize);
			if(!$myPoster){
				$myPoster = array();
			}
			if(!$myFavPoster){
				$myFavPoster = array();
			}
			//dump($myPoster);exit;
			$data = array_merge($myPoster, $myFavPoster);
		}
		
		//$data = D('Poster','poster')->getPosterByParam($map,$page,$pagesize);
		foreach ($data as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
			if($val['deadline'] >= time()){
				$apiarr[$key]['useful'] = 1;
			}else{
				$apiarr[$key]['useful'] = 2;
			}
			$apiarr[$key]['time'] = date("Y-m-d H:i",$val['cTime']);
			$apiarr[$key]['stname'] = D('PosterSmallType','poster')->getTypeName($val['type']);
			$apiarr[$key]['pname'] = D('PosterType','poster')->getTypeName($val['pid']);
			if($val['cover']){
				$apiarr[$key]['contentimg'] = SITE_URL.'/data/uploads/'.$val['cover'];
			}else{
				$apiarr[$key]['contentimg'] = SITE_URL.'/apps/poster/Tpl/desktop/Public/images/default_post.gif';
			}
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
	}
	
	private function getMyPoster($uid,$useful,$page,$pagesize){
		$map['uid'] = $uid;
		if($useful == 1){
			$map['deadline'] = array('EGT', time());
		}else if($useful == 2){
			$map['deadline'] = array('LT', time());
		}
		$data = D('Poster','poster')->getPosterByParam($map,$page,$pagesize);
		return $data;
	}
	private function getMyFavPoster($uid,$useful,$page,$pagesize){
		$favinfo = D('Poster','poster')->getFavPosterInfo($uid);
		foreach ($favinfo as $v){
			$ids[] = $v['fid'];
		}
		$map['id'] = Array('in',$ids);
		if($useful == 1){
			$map['deadline'] = array('EGT', time());
		}else if($useful == 2){
			$map['deadline'] = array('LT', time());
		}
		$data = D('Poster','poster')->getPosterByParam($map,$page,$pagesize);
		return $data;
	}
	
	/**
	 * @title  获取指定id的招贴详情
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-10
	 */
	function getDetail(){
		$uid = $_POST['uid'];
		$pid = $_POST['pid'];
		
		$arrkey = array(
				'uid' => 'uid',
				'title' => 'title',
				'postid' => 'id',
				'content' => 'content',
				'contact' => 'contact'
		);
		$posterData = D ( 'Poster','poster' )->getPosterById ( $pid );
		if (!$posterData) {
			$list['status'] = 2;
			$list['statusCode'] = '该信息不存在或已被删除';
			$list['data'] = array();
			return $list;
		}
		$apiarr = getApiArray($posterData, $arrkey);
		$apiarr['uname'] = getUserName($posterData['uid']);
		$apiarr['uhead'] = getUserFace($posterData['uid']);
		$apiarr['contact'] = $apiarr['contact'];
		//是否过期,0已过期,1未过期
		if($posterData['deadline'] >= time()){
			$apiarr['useful'] = 1;
		}else{
			$apiarr['useful'] = 0;
		}
		//是否有权限删除该招贴，0不可以1可以
		$apiarr['selfs'] = 0;
		if($posterData['uid'] == $uid){
			$apiarr['selfs'] = 1;
		}
		$apiarr['time'] = date("Y-m-d H:i",$posterData['cTime']);
		$apiarr['etime'] = date("Y-m-d H:i",$posterData['deadline']);
		$apiarr['stname'] = D('PosterSmallType','poster')->getTypeName($posterData['type']);
		$apiarr['pname'] = D('PosterType','poster')->getTypeName($posterData['pid']);
		$apiarr['area'] = D('Area', 'poster')->getAreaInfo($posterData['address_province'].','.$posterData['address_city']);
		$apiarr['share'] = 0;
		$apiarr['price'] = '';
		$apiarr['position'] = '';
		$apiarr['square'] = '';
		$apiarr['rent'] = '';
		$apiarr['reply'] = D('Poster','poster')->getReplyCount($pid);
		$apiarr['content'] = htmlspecialchars_decode(stripslashes($apiarr['content']));
		if($posterData['cover']){
			$apiarr['contentimg'] = SITE_URL.'/data/uploads/'.$posterData['cover'];
		}else{
			$apiarr['contentimg'] = SITE_URL.'/apps/poster/Tpl/desktop/Public/images/default_post.gif';
		}
		$posterType = D( 'PosterType','poster' )->getType ( $posterData ['pid'] );
		$posterTypeExtraField = D( 'PosterType','poster' )->getExtraField ( $posterType ['extraField'] );
		foreach ($posterTypeExtraField as $key => $val){
			if($posterData[$val]){
				if($key == '位置'){
					$apiarr['position'] = $posterData[$val];
				}else if($key == '面积'){
					$apiarr['square'] = $posterData[$val];
				}else if($key == '租金'){
					$apiarr['rent'] = $posterData[$val];
				}else if($key == '分享方式'){
					$share = $posterData[$val];
					$share = str_replace('赠送', 1, $share);
					$share = str_replace('借用', 2, $share);
					$share = str_replace('交换', 3, $share);
					$apiarr['share'] = $share;
				}else if($key == '价格'){
					$apiarr['price'] = $posterData[$val];
				}
			}
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
	}
	
	
	/**
	 * @title  发布一个招贴
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-10
	 */
	function Post(){
		$map['title'] = t ( h ( $_POST['title'] ) );
		$map['type'] = intval ( $_POST['stid'] );
		$map['pid'] = intval ( $_POST['pid'] );
		$map['content'] = h ( $_POST['content'] );
		$map['contact'] = t ( $_POST['contact'] );
		$map['uid'] = $_POST['uid'];
		$map['cTime'] = time ();
		//var_dump($map);
		$etime = $_POST['etime'];
		if ($etime) {
			if($etime < time ()){
				$list['status'] = 2;
				$list['statusCode'] = '结束时间不得小于发布时间';
				$list['data'] = array();
				return $list;
			}else{
				$map ['deadline'] = $etime;
			}
		} else {
			$map ['deadline'] = NULL;
		}

		if( $map['pid'] != 4 ){
			if( $map['type'] == null ){
				$list['status'] = 2;
				$list['statusCode'] = '类别没有选择';
				$list['data'] = array();
				return $list;
			}
		}

		// 检查详细介绍
		if (get_str_length ( $map ['content'] ) <= 0) {
			$list['status'] = 2;
			$list['statusCode'] = '详细介绍不能空';
			$list['data'] = array();
			return $list;
		}

		$map ['address_province'] = $_POST['province'];
		$map ['address_city'] = $_POST['city'];

		$share = $_POST['share'];
		$price = $_POST['price'];
		$area = $_POST['area'];
		$square = $_POST['square'];
		$rent = $_POST['rent'];
		if($share){
			$word = '';
			if($share == 1){
				$word = '赠送';
			}else if($share == 2){
				$word = '借用';				
			}else if($share == 3){
				$word = '交换';
			}
			$map['extra1'] = $word;
		}else if($price){
			$map['extra1'] = $price;
		}else if($area || $square || $rent){
			$map['extra1'] = $area;
			$map['extra2'] = $square;
			$map['extra3'] = $rent;
		}

		// 得到上传的图片
		if($_FILES['img']){
			$options ['userId'] = $map['uid'];
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$cover = X ( 'Xattach' )->upload ( 'poster_cover', $options );
			if ($cover ['status']) {
				$map ['cover'] = $cover ['info'] [0] ['savepath'] . $cover ['info'] [0] ['savename'];
			} else {
				$list['status'] = 2;
				$list['statusCode'] = $cover ['info'];
				$list['data'] = array();
				return $list;
			}
		}
		
		$rs = D ( 'Poster','poster' )->add ( $map );
		if ($rs) {
			// 发微薄
			$_SESSION ['new_poster'] = 1;
			// 积分
			X ( 'Credit' )->setUserCredit ( $map['uid'], 'add_poster' );
			$list['status'] = 0;
			$list['statusCode'] = '发布成功';
		} else {
			$list['status'] = 2;
			$list['statusCode'] = '发布失败';
		}
		return $list;
	}
	
	/**
	 * @title  删除指定招贴
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-10
	 */
	function Delete(){
		$uid = $_POST['uid'];
		$pid = $_POST['pid'];
		
		$posterInfo = D('Poster','poster')->getPosterById($pid);
		if (!$posterInfo) {
			$list['status'] = 2;
			$list['statusCode'] = '该信息不存在或已被删除';
			return $list;
		}
		if($posterInfo['uid'] != $uid){
			$list['status'] = 2;
			$list['statusCode'] = '没有删除权限';
			return $list;
		}
		$res = D('Poster','poster')->delPoster( $pid );
		if ($res) {
			// 积分
			X ( 'Credit' )->setUserCredit ( $uid, 'delete_poster' );
			$list['status'] = 0;
			$list['statusCode'] = '删除成功';
		}else{
			$list['status'] = 2;
			$list['statusCode'] = '删除失败';
		}
		return $list;
	}
	
	/**
	 * @title  获取指定招贴的评论列表
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-10
	 */
	function getComments(){
		$uid = $_POST['uid'];
		$pid = $_POST['pid'];
		$page = $_POST['page'];
		$value = $_POST['value'];
		
		$arrkey = array(
				'uid' => 'uid',
				'cid' => 'id',
				'content' => 'comment'
		);
		$replyList = D('Poster','poster')->getReplyByPosterid($pid, $page, $value);
		foreach ($replyList as $key => $val){
			$apiarr[$key] = getApiArray($val, $arrkey);
			$apiarr[$key]['uname'] = getUserName($val['uid']);
			$apiarr[$key]['uhead'] = getUserFace($val['uid']);
			$apiarr[$key]['time'] = date("Y-m-d H:i",$val['cTime']);
			$apiarr[$key]['content'] = formatComment($apiarr[$key]['content']);
		}
		$list['status'] = 0;
		$list['statusCode'] = '连接成功';
		$list['data'] = $apiarr;
		return $list;
	}
	
	/**
	 * @title  发布评论
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-2-11
	 */
	function comment(){
		$uid = $_POST['uid'];
		$pid = $_POST['pid'];
		$replyto = $_POST['replyto'];
		$content = $_POST['content'];
		$repost = $_POST['repost'];
		
		$posterInfo = D('Poster','poster')->getPosterById($pid);
		
		// 被回复内容
		$former_comment = array();
		if ( $_POST['to_id'] > 0 )
			$former_comment = D('Poster','poster')->getReplyById($_POST['to_id']);
		
		// 插入新数据
		$map['type']	= 'poster'; // 应用名
		$map['appid']	= $pid;
		$map['appuid']	= $posterInfo['uid'];
		$map['uid']		= $uid;
		$map['comment']	= t(getShort($content, $GLOBALS['ts']['site']['length']));
		$map['cTime']	= time();
		$map['toId']	= $replyto;
		$map['status']	= 0; // 0: 未读 1:已读
		$map['quietly']	= 0;
		$map['to_uid']	= $former_comment['uid'] ? $former_comment['uid'] : $posterInfo['uid'];
		$map['comment_ip'] = get_client_ip();
		$url = urldecode(U('poster/Index/posterDetail',array('id'=>$posterInfo['id'])));
		$map['data']	= serialize(array(
				'title' => t(html_entity_decode($posterInfo['title'],ENT_QUOTES)),
				'url'					=> $url,
				'table'					=> 'poster',
				'id_field'				=> 'id',
				'comment_count_field'	=> 'commentCount',
		));
		$res = D('Poster','poster')->addReply($map);
		
		// 避免命名冲突
		unset($map['data']);
		
		if ($res) {
				
			//积分处理
			$setCredit = X('Credit');
			if($map['toId'] > 0 && $uid != $map['to_uid']){
				$setCredit->setUserCredit($uid,'reply_comment')
				->setUserCredit($map['to_uid'],'replied_comment');
			}else if($uid != $map['to_uid']){
				$setCredit->setUserCredit($uid,'add_comment')
				->setUserCredit($uid,'is_commented');
			}
			// 发表微广播
			if ($repost) {
				$from_data = array('app_type'=>'local_app', 'app_name'=>'poster', 'title'=>$posterInfo['title'], 'url'=>$url);
				$from_data = serialize($from_data);
				D('Weibo','weibo')->publish($uid,
				array(
				'content' => html_entity_decode(
				$content . ($replyto > 0?(' //@' . getUserName($former_comment['uid']) . ' :' . $former_comment['comment']):''),
				ENT_QUOTES
				),
				), 0, 0, '', '', $from_data);
			}
		
			$list['status'] = 0;
			$list['statusCode'] = '评论成功';
		}else{
			$list['status'] = 2;
			$list['statusCode'] = '评论失败';
		}
		return $list;
	}
	
	function delcomment(){
		$uid = $_POST['uid'];
		$cid = $_POST['cid'];
		
		if(model('GlobalComment')->deleteComment($cid, $uid)){
			//积分处理
			$setCredit = X('Credit');
			$setCredit->setUserCredit($uid,'delete_comment');
			$data['status'] = 0;
			$data['statusCode'] = '删除成功';
		}else{
			$data['status'] = 2;
			$data['statusCode'] = '删除失败';
		}
		return $data;
	}
	
	
}