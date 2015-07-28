<?php
class IndexAction extends Action{

	const INDEX_TYPE_WEIBO = 0;
	const INDEX_TYPE_GROUP = 1;
	const INDEX_TYPE_ALL   = 2;
	
	/**
	 +----------------------------------------------------------
	 * 微博信息页初始化函数
	 +----------------------------------------------------------
	 * @author 小波 2013-6-7
	 +----------------------------------------------------------
	 */
	function _initialize() {
		$data ['followTopic'] = D ( 'Follow', 'weibo' )->getTopicList ( $this->mid );
		$this->assign ( $data );
	}
	
	function init(){
		echo './Public/miniblog.js';
	}
	
	/**
	 +----------------------------------------------------------
	 * 为新版桌面系统集成一个微博列表页面
	 +----------------------------------------------------------
	 * @author 小波 2013-6-7
	 +----------------------------------------------------------
	 */
	function index(){
		Session::pause();
        global $ts;

        //SamPeng 2011.12.15重构整个方法
        $install_app = $ts['install_apps'];

        $index_type = intval( $_GET ['type'] );  //0=我关注的、1=群组微博、2=正在发生
        $weibo_type = h( $_GET ['weibo_type'] ); //orignal=原创、0=微博、1=图片微博、2=视频

        //$weibo_config = model ( 'Xdata' )->lget ( 'weibo' );

        //判断是动态还是微博,兼容1.6的代码

        //if (($show_feed = $weibo_config['openDynamic'] && intval($_COOKIE['feed']))) {
        //  $data = $this->__getDynamic($index_type); //显示动态
        //  $data['show_feed'] = $show_feed;
        //} else {
            $data = $this->__getWeiboList($install_app, $index_type, $weibo_type); //显示微博列表
            $data['type'] = $index_type;
            $data['weibo_type'] = $weibo_type;
        //}

        $this->__assignTabSwitch($index_type);

        if(!empty($weibo_type)) {
            $this->assign('typeClass',"on");
            $this->assign('view','block');
        }else{
            $this->assign('typeClass','off');
            $this->assign('view','none');
        }

        $this->assign ( $data );
        $this->setTitle (L('my_index'));
        $this->display ();
	}
	
	private function __getWeiboList($install_app,$index_type,$weibo_type)
	{
		global $ts;
	
		// 关注的分组列表
		$myFollowData = $this->__paramUserFollowGroup($index_type);
		$data  = $myFollowData;
	
		$data['indexType']   = $index_type;
		$temp = $ts['my_group_list'];
		$group_list = array();
		foreach($temp as $value){
			if($value['openWeibo']){
				$group_list[] = $value;
			}
		}
		$data['group_list']  = $group_list;
	
		$data['gid']         = intval($_GET['gid']);
	
		$data['hasGroupWeibo']  = $this->__hasGroupWeibo($group_list);
	
		if($index_type == self::INDEX_TYPE_WEIBO){
			$data['weibo_menu'] = array(
					''  => L('all'),
					'original' => L('original'),
			);
			Addons::hook('home_index_weibo_tab', array(&$data['weibo_menu']));
		}
		switch ($index_type) {
			case self::INDEX_TYPE_WEIBO:
				$data ['list'] = D('Operate', 'weibo')->getHomeList ( $this->mid, $weibo_type, '', '', $data ['follow_gid'] );
				break;
			case self::INDEX_TYPE_GROUP:
				$data ['list'] = D('WeiboOperate','group')->getHomeList($this->mid, $data['gid'], '', '');
				break;
			case self::INDEX_TYPE_ALL:
				$order = 'weibo_id DESC';
				$data['list']  = D('Operate','weibo')->doSearchTopic("",$order,$this->mid);
				break;
			default:
				$data ['list'] = D('Operate', 'weibo')->getHomeList ( $this->mid, $weibo_type, '', '', $data ['follow_gid'] );
		}
	
		if($data['list']['data']){
			// 最新一条微博的Id (countNew时使用)
			$_last_weibo = reset($data ['list'] ['data']);
			$data ['lastId'] = $_last_weibo['weibo_id'];
			$_since_weibo = end($data ['list'] ['data']);
			$data['sinceId'] = $_since_weibo['weibo_id'];
		}
		return $data;
	}

	private function __assignTabSwitch ($index_type)
	{
		//判断当前使用哪一个tab
		switch ($index_type) {
			case self::INDEX_TYPE_WEIBO:
				$this->assign('weibo_tab', 'on');
				break;
			case self::INDEX_TYPE_GROUP:
				$this->assign('group_tab', 'on');
				break;
			case self::INDEX_TYPE_ALL:
				$this->assign('all_tab', 'on');
				break;
			default:
				$this->assign('weibo_tab','on');
		}
	}
	
	private function __paramUserFollowGroup($type){
		$data ['follow_gid'] = is_numeric ( $_GET ['follow_gid'] ) ? $_GET ['follow_gid'] : 'all';
		$group_list = D ( 'FollowGroup', 'weibo' )->getGroupList ( $this->uid );
		//dump($group_list);exit();
		//兼容旧风格包的逻辑生成两个数组
		$split_result = $this->__splitFollowGroup($group_list, $data['follow_gid']);
		$data['group_list_1'] = $split_result['group_list_1'];
		$data['group_list_2'] = $split_result['group_list_2'];
	
		$firstGroup =  array('follow_group_id'=>'all','title'=>L('following_my'));
		if($data['follow_gid'] == 'all'){
			$data['group_now']    = $firstGroup;
		}else{
			$data['group_now']    = $split_result['now'];
		}
	
		array_unshift($group_list,$firstGroup);
	
		$data['follow_group_list']   = $group_list;
		return $data;
	}
	
	private function __splitFollowGroup($group_list,$gid)
	{
		$res = array();
		if (! empty ( $group_list )) { // 关注分组
			$group_count = count ( $group_list );
			for($i = 0; $i < $group_count; $i ++) {
				if ($group_list [$i] ['follow_group_id'] != $gid) {
					$group_list [$i] ['title'] = $this->__shortForGroupTitle($group_list[$i]['title']);
				}else{
					$res['now'] = $group_list[$i];
				}
				if ($i < 2) {
					$res ['group_list_1'] [] = $group_list [$i];
				} else {
					if ($group_list [$i] ['follow_group_id'] == $gid) {
						$res ['group_list_1'] [2] = $group_list [$i];
						continue;
					}
					$res ['group_list_2'] [] = $group_list [$i];
				}
			}
			if (empty ( $res ['group_list_1'] [2] ) && ! empty ( $res ['group_list_2'] [0] )) {
				$res ['group_list_1'] [2] = $res ['group_list_2'] [0];
				unset ( $res ['group_list_2'] [0] );
			}
		}
		return $res;
	}
	
	private function __hasGroupWeibo($group_list)
	{
		$hasGroupList = $group_list && !empty($group_list);
		return $hasGroupList;
	}
	
	private function __shortForGroupTitle($title)
	{
		return (strlen ( $title ) + mb_strlen ( $title, 'UTF8' )) / 2 > 8 ? getShort ( $title, 3 ) . '...' : $title;
	}
	
    //加载评论
    function loadcomment(){
        $intMinId = intval( $_POST['id'] );
        $data['weibo_id'] = $intMinId;
        $data['quick_reply'] = intval($_POST['quick_reply']);
        $data['quick_reply_uname'] = t($_POST['quick_reply_uname']);
        $data['quick_reply_comment_id'] = intval($_POST['quick_reply_comment_id']);
        $data['callback'] = t($_POST['callback']);
        $data['data']  = D('Operate')->where('weibo_id='.$intMinId)->find();
        $data['privacy'] = D('UserPrivacy','home')->getPrivacy($this->mid,$data['data']['uid']);
        $data['randtime'] = ($data['quick_reply_comment_id'])?$data['quick_reply_comment_id']:$data['data']['weibo_id'] ;
        if(!$data['quick_reply']) $data['list'] =  D('Comment')->getComment($intMinId);
        if($intMinId){
            $data['weibo_id'] = $intMinId;
        }else{
            $map['comment_id'] = $data['quick_reply_comment_id'];
            $data['weibo_id'] = D('Comment')->getField('weibo_id',$map);
        }

        $this->assign( $data );
        $this->display();
    }


    //加载更多的
    function loadmore(){
        $data['showfeed'] = intval($_REQUEST['showfeed']);
        $data['lastId'] = intval($_POST['since']);
        $data['type']   = t($_POST['type']);
        $data['follow_gid'] = intval($_POST['follow_gid']);
        $uid = isset($_POST['hasUid']) ? intval($_POST['hasUid']):$this->mid;
        $data['simple'] = intval($_POST['simple']);
    	$data['list'] = D('Operate')->loadMore($uid,$data['lastId'],$data['type'],$data['follow_gid'],$data['simple']);
		
    	$this->assign($data);
    	$this->display();
    }
    
	//班级动态加载更多的
    public function Classloadmore(){
        $data['showfeed'] = intval($_REQUEST['showfeed']);
        $data['lastId'] = intval($_POST['since']);
        $data['type']   = t($_POST['type']);
        $data['follow_gid'] = intval($_POST['follow_gid']);
		$data['classid'] = intval($_POST['classid']);
        $data['simple'] = intval($_POST['simple']);
    	$data['list'] = D('Operate')->ClassloadMore($data['type'],4,$data['classid'],$data['lastId'],$data['follow_gid']);
		$this->assign($data);
		//var_dump($data);
    	$this->display();
    }
	
    function loadnew(){
    	$data['showfeed'] = intval($_REQUEST['showfeed']);
    	$data['lastId'] = intval($_POST['since']);
    	$data['type']   = t($_POST['type']);
    	$data['follow_gid'] = intval($_POST['follow_gid']);
    	$data['limit']      = intval($_POST['limit']);
    	$uid = isset($_POST['hasUid']) ? intval($_POST['hasUid']):$this->mid;
    	$data['simple'] = intval($_POST['simple']);
    	$data['list'] = D('Operate')->loadNew($uid,$_POST['since'],$data['limit'],$data['type'],$data['follow_gid'],$simple);
    	
    	$this->assign($data);
    	$this->display('loadmore');
    }

    //查看最新的
    function countnew(){
    	$data['showfeed'] = intval($_REQUEST['showfeed']);
    	$data['lastId'] = intval($_POST['lastId']);
    	$data['type']   = t($_POST['type']);
    	$data['follow_gid'] = intval($_POST['follow_gid']);
    	//重构该处，完全没有用到的功能。
    	//$data['since'] = $list[0]['weibo_id'];
    	$uid = isset($_POST['hasUid']) ? intval($_POST['hasUid']):$this->mid;
    	$data['limit'] = D('Operate')->countNew($uid,$data['lastId'],$data['type'],$data['follow_gid']);
    	$this->assign($data);
    	$this->display();
    }

    //@xxx
    function searchuser(){
    	$name = t($_REQUEST['n']);
    	$list = M('user')->where("uname LIKE '{$name}%'")->field('uid,uname')->findall();
    	exit( json_encode($list));
    }
}
?>