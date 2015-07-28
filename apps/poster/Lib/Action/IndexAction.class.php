<?php
/**
     * GiftAction
     * 礼物控制层
     *
     * @uses
     * @package
     * @version
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
class IndexAction extends Action {
	private $icopath = "";
	protected $app_alias;
	public function _initialize() {
		// 参数转义
		$this->icopath = '../Public/images/ico/';
		$this->assign ( 'icopath', $this->icopath );
		
		global $ts;
		$this->app_alias = $ts ['app'] ['app_alias'];
	}
	public function index() {

		//var_dump(U('poster/Index/ajaxAddPoster'));
		$uid = array ();
		if ($_GET ['order'] == 'following') {
			$following = M ( 'weibo_follow' )->field ( 'fid' )->where ( "uid={$this->mid} AND type=0" )->findAll ();
			foreach ( $following as $v ) {
				$uid [] = $v ['fid'];
			}
			
			$this->setTitle ( "我关注的人的{$this->app_alias}" );
		}elseif ($_GET ['t']==1){
			$t= $_GET ['t'];
		}else {
			$this->setTitle ( "最新{$this->app_alias}" );
			$re=1;
			$this->assign('re',$re);
		}
		
		if($_GET['searchtxt'] != ''){
			$key = $_GET['searchtxt'];
		}
		$this->__setAssign ( $uid, $key );
		$this->display ();
	}
	public function personal() {
		$this->__setAssign ( $this->uid );
		$this->assign ( 'uid', $this->uid );
		$this->assign ( 'name', getUserName ( $this->uid ) );
		$this->setTitle ( "我的{$this->app_alias}" );
		$this->display ();
	}
	public function addPosterSort() {
		$posterTypeDao = D ( 'PosterType' );
		$posterType = $posterTypeDao->getType ();
		$this->assign ( 'posterType', $posterType );
		$this->setTitle ( "发布{$this->app_alias}" );
		$this->display ();
	}
	public function posterDetail() {
		
		$posterTypeDao = D ( 'PosterType' );
		$poster = D ( 'Poster' );
		$id = intval ( $_GET ['id'] );
		if ($id == 0) {
			$this->error ( "错误的信息地址.请检查后再访问" );
			exit ();
		}
		
		$posterData = $poster->getPoster ( $id, $this->mid );
		if (! $posterData) {
			$this->assign ( 'jumpUrl', U ( 'poster/Index/index', 'iframe=yes') );
			$this->error ( "这个信息被删除或者不允许查看" );
			exit ();
		}
		
		$posterType = $posterTypeDao->getType ( $posterData ['pid'] );
		
		$posterTypeExtraField = $posterTypeDao->getExtraField ( $posterType ['extraField'] );
		unset ( $posterType ['extraField'] );
		
		if ($posterData ['uid'] == $this->mid) {
			$posterData ['name'] = '我';
			$this->assign ( 'admin', 1 );
		} else {
			$posterData ['name'] = getUserName ( $posterData ['uid'] );
		}
		$loginUid = $this->mid;
		$this->assign ( 'loginUid', $loginUid );
		$this->assign ( 'poster', $posterData );
		$this->assign ( 'extraField', $posterTypeExtraField );
		$this->assign ( 'type', $posterType );
		$this->display ();
	}
	public function doDeletePoster() {
		$id = intval ( $_POST ['id'] );
		if (0 == $id) {
			echo - 3;
		} else {
			$poster = D ( 'Poster' );
			if ($res = $poster->deletePoster ( $id, $this->mid )) {
				// 积分
				X ( 'Credit' )->setUserCredit ( $this->mid, 'delete_poster' );
			}
			echo $res;
		}
	}
	private function __setAssign($uid = null, $key=null) {
		
		$poster = D ( 'Poster' );
		$pid = intval ( $_GET ['pid'] ) ? intval ( $_GET ['pid'] ) : null;
		$stid = intval ( $_GET ['stid'] ) ? intval ( $_GET ['stid'] ) : null;
		
		$posterData = $poster->getPosterList ( $pid, $stid, $uid, $key );
		$this->getPosterType ( $poster );
		
		$posterData['posterLimit'] = $ts['userAppLimit']['poster']['rolearray'];
		foreach ($posterData['data'] as $key => $value) {
			$posterData['data'][$key]['content'] = stripslashes(strip_tags($posterData['data'][$key]['content']));
			$posterData['data'][$key]['content'] = str_replace('&nbsp;', '', $posterData['data'][$key]['content'] );
			$posterData['data'][$key]['content'] = htmlspecialchars($posterData['data'][$key]['content']);
			
			$posterData['data'][$key]['title'] = preg_replace("/s+([ $])/", "\1", $posterData['data'][$key]['title']);
		}
		$this->assign ( $posterData );
	}
	private function getPosterType($poster) {
		$posterTypeDao = D ( 'PosterType' );
		$posterSmallTypeDao = D ( 'PosterSmallType' );
		$posterType = $posterTypeDao->getType ();
		$posterTree = array ();
		foreach ( $posterType as $value ) {
			$id = $value ['id'];
			$name = $value ['name'];
			$type = $value ['type'];
			if ($type) {
				$posterSmallType = $posterSmallTypeDao->getPosterSmallType ( $type );
			}
			$posterNode = array (
					"id" => $id,
					"name" => $name,
					"children" => $posterSmallType 
			);
			array_push ( $posterTree, $posterNode );
		}
		// $num = count($posterType);
		// for($i=0; $i<$num; $i++){
		// $id = $posterType[$i][id];
		// $name = $posterType[$i][name];
		// $type = $posterType[$i][type];
		// $posterSmallType = $posterSmallTypeDao->getPosterSmallType($type);
		
		// }
		// foreach($posterType as $value){
		// $id = $value['id'];
		// if(isset($value['type']) && $id == intval($_GET['pid'])){
		// $posterSmallType =
		// $posterSmallTypeDao->getPosterSmallType($value['type']);
		// }
		// }
		
		// $posterSmallType = $this->getPosterCount($poster,$posterSmallType);
		// $this->assign('posterType',$posterType);
		// $this->assign('type',$posterSmallType);
		$this->assign ( 'posterTree', $posterTree );
	}
	private function getPosterCount($poster, $posterSmallType) {
		$tableName = $poster->getTableName ();
		// $otherWhere = $this->private;
		if (! empty ( $posterSmallType )) {
			for($i = 0; $i < count ( $posterSmallType ); $i ++) {
				// if(isset($otherWhere)){
				// $where = "type = {$posterSmallType[$i]['id']} AND
				// ".$otherWhere;
				// }else{
				$where = "type = {$posterSmallType[$i]['id']}";
				// }
				$sql [] = "select '{$posterSmallType[$i]['id']}' as `id`,count(1) as count from  {$tableName} where {$where}";
			}
		}
		$sql = implode ( ' union all ', $sql );
		$count = $poster->query ( $sql );
		$temp_array = array ();
		foreach ( $count as $value ) {
			$temp_array [$value ['id']] = $value ['count'];
		}
		$result = $posterSmallType;
		foreach ( $result as &$value ) {
			$value ['count'] = $temp_array [$value ['id']];
		}
		return $result;
	}
	public function addPoster() {
		$typeId = intval ( $_GET ['typeId'] );
		if (empty ( $typeId ))
			$this->error ( '参数有误' );
		
		$posterTypeDao = D ( 'PosterType' );
		$poster = $posterTypeDao->getType ( $typeId );
		if (empty ( $poster )) {
			$this->error ( '参数有误' );
		}
		$posterSmallTypeDao = D ( 'PosterSmallType' );
		$posterSmallType = $posterSmallTypeDao->getPosterSmallType ( $poster ['type'] );
		$this->assign ( $poster );
		$this->assign ( 'smallType', $posterSmallType );
		// 初始化截止日期
		$this->assign ( 'deadline', date ( "Y-m-d H:i:s", time () + 90 * 24 * 3600 ) );
		
		$this->setTitle ( "发布{$this->app_alias}" );
		$this->display ();
	}
	public function editPoster() {
		$posterDao = D ( 'Poster' );
		$posterData = $posterDao->getPoster ( $_GET ['id'], $this->mid );
		$posterTypeDao = D ( 'PosterType' );
		$poster = $posterTypeDao->getType ( intval ( $_GET ['typeId'] ), intval ( $_GET ['id'] ) );
		if (empty ( $poster )) {
			$this->error ( '参数有误' );
		}
		$posterSmallTypeDao = D ( 'PosterSmallType' );
		$userInfo ['areaid'] = $posterData ['address_province'] . ',' . $posterData ['address_city'];
		$posterData ['deadline'] && $posterData ['deadline'] = date ( "Y-m-d H:i:s", $posterData ['deadline'] );
		$posterSmallType = $posterSmallTypeDao->getPosterSmallType ( $poster ['type'] );
		$provinceid = $posterData['address_province'];
		$cityid = $posterData['address_city'];
		$province = $posterDao->getArea($provinceid, $cityid);
		$address['province'] = $province[0][title];
		$address['provincenn'] = $province[0]['area_id'];
		$address['city'] = $province[1][title];
		$address['citynn'] = $province[1]['area_id'];
		$this->assign ( 'address', $address );
		$this->assign ( 'smallType', $posterSmallType );
		$this->assign ( 'userInfo', $userInfo );
		$this->assign ( 'poster', $posterData );
		$this->assign ( $poster );
		$this->display ();
	}
	public function doEditPoster() {
		$dao = D ( 'Poster' );
		$condition ['id'] = intval ( $_POST ['id'] );
		$map ['title'] = t ( $_POST ['title'] );
		$map ['type'] = intval ( $_POST ['type'] );
		$map ['content'] = h ( $_POST ['explain'] );
		$map ['contact'] = t ( $_POST ['contact'] );
		$map ['address_province'] = $_POST ['province'];
		$map ['address_city'] = $_POST ['cityshow'];
	
		if(is_numeric($map ['address_city'])){
		}
		else{
			$where['title']=$map['address_city'];
			$result['title'] = M('area')->where($where)->getField('area_id');
			$map['address_city']=$result['title'];
		}

		$map = $this->_extraField ( $map, $_POST );
		// 检查详细介绍
		if (get_str_length ( $map ['content'] ) <= 0) {
			echo json_encode(array('status'=>0,'info'=>'详细介绍不能为空'));
			exit();
		}
		if ($_POST ['deadline']) {
			
			$timeData=$this->_paramDate ( $_POST ['deadline'] );
			$map ['deadline'] = $deadline =$timeData['all'];
			$sendPosterTime = $dao->where ( 'id=' . intval ( $_POST ['id'] ) )->getField ( 'cTime' );
			if($timeData['year'] >= '2038'){
				echo json_encode(array('status'=>0,'info'=>'结束时间不能超过2038年'));
				exit();
			}
			elseif($deadline < $sendPosterTime ){
			echo json_encode(array('status'=>0,'info'=>'结束时间不得小于发布时间'));
			exit();
			}
		} else {
			//$map ['deadline'] = NULL;
			echo json_encode(array('status'=>0,'info'=>'结束时间不能超过2038年'));
			exit();
		}
		// 得到上传的图片
		$option = array ();
		if ($_FILES ['cover'] ['size'] > 0) {
			$options ['userId'] = $this->mid;
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$cover = X ( 'Xattach' )->upload ( 'poster_cover', $options );
			if ($cover ['status']) {
				$map ['cover'] = $cover ['info'] [0] ['savepath'] . $cover ['info'] [0] ['savename'];
			} else {
				$this->error ( $cover ['info'] );
			}
		}
		
		$rs = $dao->where ( $condition )->save ( $map );
		//echo M()->getLastSql();
		
		if ($rs==0) {
			echo json_encode(array('status'=>0,'info'=>'您未做任何修改！'));
			exit();
		} else if ($rs>=1){
			echo json_encode(array('status'=>1,'info'=>'发布成功'));
			exit();
		}else {
			echo json_encode(array('status'=>0,'info'=>'发布失败'));
			exit();
		}
	}
	private function _paramDate($date) {
		$date_list = explode ( ' ', $date );
		list ( $year, $month, $day ) = explode ( '-', $date_list [0] );
		list ( $hour, $minute, $second ) = explode ( ':', $date_list [1] );
		$data['all']= mktime ( $hour, $minute, $second, $month, $day, $year );
		$data['year']=$year;
		//return mktime ( $hour, $minute, $second, $month, $day, $year );
		return $data;
	}
	public function doAddPoster() {
		$map ['title'] = t ( h ( $_POST ['title'] ) );
		$map ['type'] = intval ( $_POST ['type'] );
		$map ['pid'] = intval ( $_POST ['pid'] );
		$map ['content'] = h ( $_POST ['explain'] );
		$map ['contact'] = t ( $_POST ['contact'] );
		$map ['uid'] = $this->mid;
		$map ['cTime'] = time ();
		if ($_POST ['deadline']) {
			$map ['deadline'] = $deadline = $this->_paramDate ( $_POST ['deadline'] );
			$deadline < time () && $this->error ( "结束时间不得小于发布时间" );
		} else {
			$map ['deadline'] = NULL;
		}
	
		// 检查详细介绍
		if (get_str_length ( $map ['content'] ) <= 0) {
			$this->error ( '详细介绍不能为空' );
		}
		
		$address = explode ( ',', $_POST ['areaid'] );
		$map ['address_province'] = $address [0];
		$map ['address_city'] = $address [1];
		
		$map = $this->_extraField ( $map, $_POST );
		// 得到上传的图片
		$option = array ();
		if ($_FILES ['cover'] ['size'] > 0) {
			$options ['userId'] = $this->mid;
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$cover = X ( 'Xattach' )->upload ( 'poster_cover', $options );
			if ($cover ['status']) {
				$map ['cover'] = $cover ['info'] [0] ['savepath'] . $cover ['info'] [0] ['savename'];
			} else {
				$this->error ( $cover ['info'] );
			}
		}
		// $map['private'] = isset($_POST['friend'])?$_POST['friend']:0;
		$dao = D ( 'Poster' );
		$rs = $dao->add ( $map );
		if ($rs) {
			// 发微薄
			$_SESSION ['new_poster'] = 1;
			// 积分
			X ( 'Credit' )->setUserCredit ( $this->mid, 'add_poster' );
			$this->success ( "发布成功，继续发布！" );
		} else {
			$this->error ( "发布失败" );
		}
	}
	public function ajaxAddPoster() {
		$map ['title'] = t ( h ( $_POST ['title'] ) );
		$map ['type'] = intval ( $_POST ['type'] );
		$map ['pid'] = intval ( $_POST ['pid'] );
		$map ['content'] = h ( $_POST ['explain'] );
		$map ['contact'] = t ( $_POST ['contact'] );
		$map ['uid'] = $this->mid;
		$map ['cTime'] = time ();
		
		if ($_POST ['deadline']) {
			$timeData=$this->_paramDate ( $_POST ['deadline'] );
			$map ['deadline'] = $deadline =$timeData['all'];
		
			if($timeData['year'] >= '2038'){
				echo json_encode(array('status'=>0,'info'=>'结束时间不能超过2038年'));
				exit();
			}elseif($deadline < time ()){
				echo json_encode(array('status'=>0,'info'=>'结束时间不得小于发布时间'));
				exit();
			}
		} else {
			$map ['deadline'] = NULL;
		}
		if( $map['pid'] != 4 ){
			if( $map['type'] == null ){
				echo json_encode(array('status'=>0,'info'=>'类别没有选择'));
				exit();
			}
		}
		// 检查详细介绍
		if (get_str_length ( $map ['content'] ) <= 0) {
			echo json_encode(array('status'=>0,'info'=>'详细介绍不能为空'));
			exit();
		}
		//$address = explode ( ',', $_POST ['areaid'] );
		$map ['address_province'] = $_POST ['province'];
		$map ['address_city'] = $_POST ['cityshow'];
		
		$map = $this->_extraField ( $map, $_POST );
		// 得到上传的图片
		$option = array ();
		if ($_FILES ['cover'] ['size'] > 0) {
			$options ['userId'] = $this->mid;
			$options ['max_size'] = 2 * 1024 * 1024; // 2MB
			$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
			$cover = X ( 'Xattach' )->upload ( 'poster_cover', $options );
			if ($cover ['status']) {
				$map ['cover'] = $cover ['info'] [0] ['savepath'] . $cover ['info'] [0] ['savename'];
			} else {
				$this->error ( $cover ['info'] );
			}
		}
		// $map['private'] = isset($_POST['friend'])?$_POST['friend']:0;
		
		//判断应用的新内容是否需要审核 0 未审核 1 审核通过
		//$map['status'] = service('ForeAdmin')->isAuditApp() ? '0' : '1';
		//$school = get_school_by_uid($this->mid);
		///$map['schoolid'] = $school['id'];
		$dao = D ( 'Poster' );
		$rs = $dao->add ( $map );
		if ($rs) {
			// 发微薄
			$_SESSION ['new_poster'] = 1;
			// 积分
			X ( 'Credit' )->setUserCredit ( $this->mid, 'add_poster' );
				echo json_encode(array('status'=>1,'info'=>'发布成功'));
				exit();
		} else {
				echo json_encode(array('status'=>0,'info'=>'发布失败'));
				exit();
		}
	}
	
	private function _extraField($map, $post) {
		for($i = 1; $i < 6; $i ++) {
			if (isset ( $post ['extra' . $i] ) && ! empty ( $post ['extra' . $i] )) {
				if (is_array ( $post ['extra' . $i] )) {
					$map ['extra' . $i] = implode ( ',', $post ['extra' . $i] );
				} 
					else{
						$map ['extra' . $i] = $post ['extra' . $i];
					}
				}
			}
		return $map;
	}
	
	public function favorite(){
		$data =model('Favorite')->list_favorite_all('poster',10);
		foreach ($data['data'] as $v){
			$ids[] = $v['fid'];
		}
		$map['id'] = Array('in',$ids);
		$posterDao = D ( 'Poster' );
		$data = $posterDao->getFavorityPoster($map);
		foreach ($data['data'] as $key => $value) {
			$data['data'][$key]['content'] = stripslashes(strip_tags($value['content']));
			$data['data'][$key]['content'] = str_replace('&nbsp;', '', $data['data'][$key]['content'] );
			$data['data'][$key]['content'] = htmlspecialchars($data['data'][$key]['content']);
		}
		$this->assign($data);
		$this->display();
	}
}
