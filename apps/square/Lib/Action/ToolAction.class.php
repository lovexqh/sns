<?php
/**
 +------------------------------------------------------------------------------
 * 广场资源中心Action
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Action
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-1 上午03:28:41
 +------------------------------------------------------------------------------
 */
class ToolAction extends BaseAction {
	// 用户email
	private $email;
	// 应用名称
	private $appName;
	/**
	 * +----------------------------------------------------------
	 * 初始化
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:32:28
	 *         +----------------------------------------------------------
	 */
	public function _initialize() {
		$this->setTitle ( '工具中心' );
		// 应用名称
		parent::_initialize ();
		global $ts;
		$this->appName = $ts ['app'] ['app_alias'];
		$this->email = $_SESSION [userInfo] [email];
		$this->uid = $_SESSION ['mid'];
		// 首页统计信息
		$count ['all'] = D ( 'Tool', 'square' )->allTool (); //
		$count ['type'] = D ( 'Tool', 'square' )->allType (); // 软件所有类别 及 导航栏类型
		$this->assign ( 'count', $count );
		
		$sqcategory = model ( 'SquareCategory' )->getTrees ( 4 );
		
		$this->assign ( 'sqcategory', $sqcategory );
	}
	/**
	 * +----------------------------------------------------------
	 * 资源中心主页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:32:56
	 *         +----------------------------------------------------------
	 */
	public function index() {
		// 首页推荐资源
		$where ['p_id'] = 4;
		$recomment = M ( 'square_category' )->where ( $where )->select ();
		// dump($recomment);
		$this->assign ( 'recomment', $recomment );
		foreach ( $recomment as $key => $val ) {
			$value [$key] ['cate_name'] = $this->get_catename ( $val ['id'] );
		}
		// dump($value);
		$this->assign ( 'value', $value );
		
		$limit = 10;
		$map ['type'] = 'official';
		// 首页官方工具
		$official = D ( 'Tool', 'square' )->publicofficial_Tool ( $map, $limit );
		$this->assign ( 'official', $official );
		// 首页大众工具
		$map ['type'] = 'public';
		$public = D ( 'Tool', 'square' )->publicofficial_Tool ( $map, $limit );
		// dump($hot);exit();
		$this->assign ( 'public', $public );
		// 用户贡献榜
		$contribute = D ( 'Tool', 'square' )->Contribute ();
		$this->assign ( 'contribute', $contribute );
		
		// 首页热门软件排行
		$hot = D ( 'Tool', 'square' )->hotTool ();
		$this->assign ( 'hot', $hot );
		$this->display ();
	}
	function get_catename($map) {
		// 遍历树-子树下的信息
		$rtemp1 = M ( "square_category" )->field ( "path" )->where ( "id=" . $map )->find ();
		$rtemp2 = M ( "square_category" )->field ( "id" )->where ( "path like '" . $rtemp1 ['path'] . "%'" )->findall ();
		$rtemp3 = array ();
		foreach ( $rtemp2 as $key => $val ) {
			array_push ( $rtemp3, intval ( $val ['id'], 10 ) );
		}
		$wheres ['section'] = array (
				'in',
				$rtemp3 
		);
		
		$data ['cate'] = D ( 'Tool', 'square' )->getTree1 ( $wheres ); // 获取分类下的信息
		$data ['all'] = D ( 'Tool', 'square' )->p_o_all ( $wheres ); // 获取分类下的信息
		                                                    // dump($data);
		return $data;
	}
	
	/**
	 * +----------------------------------------------------------
	 * 资源中心列表页
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:33:27
	 *         +----------------------------------------------------------
	 */
	public function getList() {
		$map ['cateid'] = $_GET ['cateid'];
		// 遍历树-子树下的信息
		$rtemp1 = M ( "square_category" )->field ( "path" )->where ( "id=" . $map ['cateid'] )->find ();
		// echo M()->getLastSql();exit();
		$rtemp2 = M ( "square_category" )->field ( "id" )->where ( "path like '" . $rtemp1 ['path'] . "%'" )->findall ();
		// echo M()->getLastSql();exit();
		$rtemp3 = array ();
		foreach ( $rtemp2 as $key => $val ) {
			array_push ( $rtemp3, intval ( $val ['id'], 10 ) );
		}
		$wheres ['section'] = array (
				'in',
				$rtemp3 
		);
		
		$data = D ( 'Tool', 'square' )->getTree ( 10, $wheres ); // 获取分类下的信息
		                                                // dump($data);exit();
		   //  $m=$data['content'];                                           // echo M()->getLastSql();exit();
		$this->assign ( 'data', $data );
		
		//dump($m);
		$path = arrayKeyTolower ( procExecute ( "call vcPath(" . $map ['cateid'] . ");" ) );
		
		$this->assign ( 'path', $path );
		$this->display ();
	}
	public function getList1() {
		// $map['cateid']=$_GET['cateid'];
		$limit = 15;
		$map ['class_name'] = $_GET ['class_name'];
		$data = D ( 'Tool', 'square' )->getTree ( $limit, $map );
		$this->assign ( 'data', $data );
		$this->display ( 'getList' );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 展示页面
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:34:43
	 *         +----------------------------------------------------------
	 */
	public function show() {
		$id = $_GET ['id'];
		// 统计信息
		$result = M ()->table ( C ( 'DB_PREFIX' ) . "tool t," . C ( 'DB_PREFIX' ) . "tool_file f" )->where ( "t.id=f.id and t.id=$id" )->find ();
		// echo M()->getLastSql();exit();
		// 统计下载次数
		$downCount = D ( 'ToolDown', 'square' )->CountNum ( $id );
		$this->assign ( 'downCount', $downCount );
		
		if ($this->cookieCount ( 'tool', $id, $this->uid, '300' )) {
			$result ['readCount'] = $result ['readCount'] + 1;
			//$v=D ( 'Tool' )->where ( "id=$id and uid <> $this->mid" )->save ( $result );
			$v=D ( 'Tool' )->where ( "id=$id" )->save ( $result );
		}
		
		$this->assign ( 'result', $result );
		// 相关软件推荐
		$commend = D ( 'tool' )->order ( 'readCount DESC' )->limit ( 5 )->findall ();
		$this->assign ( 'commend', $commend );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 下载文件
	 * +----------------------------------------------------------
	 * 
	 * @param
	 *        	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return return_type <返回类型(void的方法就不用该选项)>
	 * @author 美美 2013-7-6
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-7-6 下午05:02:48
	 *         +----------------------------------------------------------
	 */
	public function down() {
		if (empty ( $_GET ['id'] ) && ! intval ( $_GET ['type'] ) > 0) {
			$this->error ( '数据错误' );
		}
		$map ['id'] = intval ( $_GET ['id'] );
		if (intval ( $_GET ['stype'] ) == 1) {
			$down = M ( 'prepare' )->where ( $map )->find ();
		} else if (intval ( $_GET ['stype'] ) == 2) {
			$down = M ( 'prepare_chapter' )->where ( $map )->find ();
		}
		$wordfilepath = UPLOAD_PATH . "/" . $down ['savepath'] . $down ['savename'];
		$filename = $down ['title'] . '.doc';
		$size = filesize ( $wordfilepath );
		file_down ( $wordfilepath, $filename, $size );
	}
	
	/**
	 * +----------------------------------------------------------
	 * 信息详情
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:37:37
	 *         +----------------------------------------------------------
	 */
	public function detil() {
		$map ['id'] = intval ( $_GET [id] );
		$data = D ( 'Tool' )->where ( $map )->find ();
		$this->assign ( 'data', $data );
		
		$file = M ( 'tool_file' )->where ( $map )->find ();
		$this->assign ( 'file', $file );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 处理收藏
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:38:10
	 *         +----------------------------------------------------------
	 */
	public function docollect() {
		$data ['uid'] = $this->uid;
		$data ['id'] = intval ( $_GET [id] );
		$data ['cTime'] = time ();
		$bool = D ( 'ResCollect' )->where ( "id=$data[id] and uid=$this->uid" )->find ();
		if ($bool) {
			$this->success ( "资源已被收藏,请勿重复收藏" );
		} else {
			$result = D ( 'ResCollect' )->add ( $data );
			if ($result) {
				D ( 'Resource' )->collectCount ( $data ['id'] );
				$this->success ( '收藏成功' );
			} else {
				$this->error ( '收藏失败!' );
			}
		}
	}
	/**
	 * +----------------------------------------------------------
	 * 下载信息详情
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:38:37
	 *         +----------------------------------------------------------
	 */
	public function downdetil() {
		$id = intval ( $_GET ['id'] );
		$result = M ( '' )->table ( "" . C ( 'DB_PREFIX' ) . "tool t," . C ( 'DB_PREFIX' ) . "tool_file f" )->where ( "t.id=$id and t.id=f.id" )->find ();
		$this->assign ( 'result', $result );

		$money = X ( 'Credit' )->getUserCredit ( $this->mid );
		$moneyType = getResConfig ( 'credit' );
		$this->assign ( 'money', $money [$moneyType] );
		$this->display ();
	}
	/**
	 * +----------------------------------------------------------
	 * 下载
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:40:10
	 *         +----------------------------------------------------------
	 */
/* 	public function dodown() {
		$mid = $this->mid;
		$map ['id'] = $_GET ['id'];
		$data =D( 'Tool' )->where ( $map )->find ();
		//dump($data);
		$result = D ( 'ToolDown')->sendGift ( $mid, $data ['uid'], $data ['price'], $map ['id'] );
		// echo M()->getLastSql();
		//dump($result);
		 //if ($result==true) {
			$resfile = M ( 'tool_file' )->where ( $map )->find ();
			// echo M()->getLastSql();exit();
			$filepath = $resfile ['saveaddress'] . "/" . $resfile ['savepath'] . $resfile ['savename'];
			$filename = $resfile ['name'];
			$size = $resfile ['toolsize'];
			file_down ( $filepath, $filename, $size );
		//}
	} */
	public function dodown(){
		$mid = $this->mid;
		$map['id'] = $_GET['id'];
	
		$data = D('Tool')->where($map)->find();
	
		$result = D('ToolDown')->sendGift($mid,$data['uid'],$data['price'],$map['id']);
		//dump($result);exit();
		if($result==true){
			$resfile  = M('tool_file')->where($map)->find();
			$filepath = $resfile['saveaddress'].$resfile['savepath'].$resfile['savename'];
			//dump($filepath);exit();
			$filename = $resfile['name'];
			$size = $resfile['toolsize'];
			file_down($filepath,$filename,$size);
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 积分管理
	 * +----------------------------------------------------------
	 * 
	 * @param
	 *        	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return Array <返回类型(void的方法就不用该选项)>
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:40:37
	 *         +----------------------------------------------------------
	 */
	public function score() {
		$data ['score'] = $_POST ['score'];
		$data ['id'] = $_POST ['id'];
		$data ['uid'] = $this->uid;
		$bool = D ( 'ResScore' )->where ( "id=$data[id] and uid=$data[uid]" )->find ();
		if (! $bool) {
			$result = D ( 'ResScore' )->add ( $data );
			if ($result) {
				$score = D ( 'ResScore' )->getScore ( $data ['id'] );
				echo $score;
			} else {
				echo $score = D ( 'ResScore' )->getScore ( $data ['id'] );
			}
		} else {
			echo $score = D ( 'ResScore' )->getScore ( $data ['id'] );
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 资源搜索
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午04:29:26
	 *         +----------------------------------------------------------
	 */
	public function doSearch() {
		$_POST ['tags'] = h ( t ( $_REQUEST ['tags'] ) );
		$_POST ['type'] = h ( t ( $_REQUEST ['type'] ) );
		if (! empty ( $_POST ['type'] )) {
			$where = "type='" . $_REQUEST ['type'] . "'";
		} else {
			$where = 1;
		}
		$limit = 10;
		if ($_POST ['tags'] != '请输入您要搜索的资源关键词，例浏览器') {
			$tags = explode ( ' ', $_REQUEST ['tags'] );
			foreach ( $tags as $v ) {
				$wheres .= " and title like '%" . $v . "%'";
			}
			$result = M ( 'tool t' )->where ( $where . $wheres )->join ( 'ts_square_category category ON t.class=category.id' )->join ( 'ts_tool_file tf on tf.id=t.id' )->field ( 't.*,tf.thumb,category.category_name' )->findPage ( $limit );
		} else
			$result = M ( 'tool t' )->where ( $where )->join ( 'ts_square_category category ON t.class=category.id' )->join ( 'ts_tool_file tf on tf.id=t.id' )->field ( 't.*,tf.thumb,category.category_name' )->findPage ( $limit );
		$this->assign ( 'data', $result );
		$this->assign ( 'tags', "搜索结果" );
		$this->display ( 'getList' );
	}
	/**
	 * +----------------------------------------------------------
	 * 排行榜
	 * +----------------------------------------------------------
	 * 
	 * @author 美美2013-6-19
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-6-19 上午09:13:39
	 *         +----------------------------------------------------------
	 */
	public function chart() {
		$_GET ['charttype'] && $map ['charttype'] = $_GET ['charttype']; // 排行榜类型(学校、人物、资源)
		                                                                  // dump($_GET['charttype']);
		$_GET ['cateid'] && $map ['cateid'] = $_GET ['cateid']; // 其他资源分类
		
		if ($map ['charttype'] == 'contribute') {
			// 活跃人物排行榜
			$data ['contribute'] = D ( 'Tool' )->field ( 'uid, count( 1 ) AS count' )->group ( 'uid' )->order ( 'count DESC' )->findPage ( 10 );
			// dump($data);//exit();
		} else {
			// 资源排行榜
			$order = 'downCount desc,readCount desc,t.time desc';
			$data ['rank'] = D ( 'Tool', 'square' )->getTree ( 10,'',$order );
		}
		// echo M()->getLastSql();
		// dump($data);exit();
		$this->assign ( $data );
		
		$this->display ();
	}
	/**
	 * +----------------------------------------------------------
	 * cookieCount
	 * +----------------------------------------------------------
	 * 
	 * @param
	 *        	$name
	 * @param
	 *        	$resid
	 * @param
	 *        	$userid
	 * @param
	 *        	$time
	 * @return
	 *
	 * @author 美美2013-3-1
	 *         +----------------------------------------------------------
	 *         创建时间：	2013-3-1 上午03:35:19
	 *         +----------------------------------------------------------
	 */
	public function cookieCount($name, $resid, $userid, $time) {
		$appname = $_COOKIE ['TS_tool'];
		$appresid = $_COOKIE ['TS_toolid'];
		$usid = $_COOKIE ['TS_tooluid'];
		if ($appname == $name && $appresid == $resid && $usid == $userid) {
			return false;
		} else {
			cookie ( 'tool', $name, $time );
			cookie ( 'toolid', $resid, $time );
			cookie ( 'tooluid', $userid, $time );
			return true;
		}
	}
}
?>
