<?php
/**
 * AdminAction
 * 社团管理
 * @uses Action
 * @package Admin
 * @version $id$
 * @copyright 2009-2011 SamPeng
 * @author SamPeng <sampeng87@gmail.com>
 * @license PHP Version 5.2 {@link www.sampeng.cn}
 */
import('admin.Action.AdministratorAction');
class AdminAction extends AdministratorAction
{

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 社团管理页面
     * @author rickeryu
     */
    public function index()
    {
//         $clublist = D('Club')->getAllClub();
//         $this->assign('clublist',$clublist);
//         $this->assign('count',count($clublist));
//         $this->display();

    	if (!empty($_POST)) {
    		$_SESSION['admin_search'] = serialize($_POST);
    	} else if (isset($_GET[C('VAR_PAGE')])) {
    		$_POST = unserialize($_SESSION['admin_search']);
    	} else {
    		unset($_SESSION['admin_search']);
    	}
    	$this->assign('isSearch', isset($_POST['isSearch'])?'1':'0');
    	$this->assign($_POST);
    	
    	$type = !empty($_REQUEST ['type']) ? $_REQUEST ['type'] : 'basic';
    	
    	$_POST['uid']   && $condition[]    =   ' uid=' . intval($_POST['uid']);
    	$_POST['id']    && $condition[]     =   ' id=' . intval($_POST['id']);
    	$_POST['name']  && $condition[]   =   ' name LIKE "%' . t($_POST['name']) . '%"';
    	$_POST['title'] && $condition[]  =   ' title LIKE "%' . t($_POST['title']) . '%"';
    	//$_POST['cid0'] 	&& $condition[]   =   ' cid0=' . intval($_POST['cid0']);
    	//$_POST['cid1'] 	&& $condition[]   =   ' cid1=' . intval($_POST['cid1']);
    	//$_POST['weibo_id']    && $condition[]     =   ' weibo_id=' . intval($_POST['weibo_id']);
    	$_POST['clubid']    && $condition[]     =   ' clubid=' . intval($_POST['clubid']);
    	$_POST['content'] && $condition[]  =   ' content LIKE "%' . t($_POST['content']) . '%"';
    	$_POST['topicid']   && $condition[]    =   ' topicid=' . intval($_POST['topicid']);
    	// 排序
    	$_POST['field'] && $_POST['order'] && $order = t($_POST['field']) . ' ' . t($_POST['order']);
    	// 分页条数
    	$_POST['limit'] && $limit = intval($_POST['limit']);
    	
    	if ('club' == $type) {
    		// 社团
    		$condition[] = 'isdel=0';
    		!$order && $order = 'ctime DESC';
    		$list = D('Club')-> getClubList(1, $condition, null, $order, $limit, 0);
    	} else if ('topic' == $type) {
    		// 帖子
    		$list = D('ClubTopic')->getTopicListAdmin(1, $condition, null, $order, $limit, 0);
    	} else if ('reply' == $type) {
    		// 回帖
    		$list = D('ClubReply')->getClubReplyList(1, $condition, null, $order, $limit, 0);
    		/*
    		 * 缓存帖子标题
    		*/
    		$tids = getSubByKey($list['data'], 'topicid');
    		$ttitles = D('ClubTopic')->field('id,clubid,title')->where('id IN (' . implode(',', $tids) . ')')->findAll();
    		foreach ($ttitles as &$v) {
    			$clubName = D('Club')->field('title')->where('id='.$v['clubid'])->select();
    			$v['clubtitle'] = $clubName['0']['title'];
    			$_topics[$v['id']] = $v;
    		}
    		$this->assign('_topics', $_topics);
    	} else if ('document' == $type) {
    		// 文件
    		$list = D('ClubDocument')->getDocumentList(1, $condition, null, $order, $limit, 0);
    	} else if('basic' == $type){
    		$this->indexbasic();
    	}
    	
    	if ('club' != $type && 'basic' != $type) {
    		/*
    		 * 缓存社团名称
    		*/
    		$gids = getSubByKey($list['data'], 'clubid');
    		$gnames = D('Club')->field('id,title')->where('id IN (' . implode(',', $gids) . ')')->findAll();
    		foreach ($gnames as $v) {
    			$_group_names[$v['id']] = $v['title'];
    		}
    		$this->assign('_group_names', $_group_names);
    	}
    	
    	$this->assign ( 'list', $list );
    	$this->assign ( 'type', $type );
    	$this->display ( 'index' . $type );
    }
    
    /**
     *+---------------------------------------------------
     *addclub——添加社团界面显示
     *+---------------------------------------------------
     * @author  rickeryu
     *+---------------------------------------------------
     */
    public function addclub(){
        $this->display();
    }
    /**
     * 
     +----------------------------------------------------------
     * Enter description here ... （方法功能的注释）
     +----------------------------------------------------------
     * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return return_type <返回类型(void的方法就不用该选项)>
     * @author RickerYu 2013-12-2
     +----------------------------------------------------------
     */
    public function doaddclub(){
    	$map['title'] = $_POST['clubname'];
    	$findClub = D( 'Club' )->getClubByClubname( $map['title'] );
    	if( $findClub ){
    		$this->error("社团名称已存在!");
    	}
    	
    	$map['description'] = $_POST['clubdesc'];
    	$map['type'] = $_POST['clubtype'];
    	$map['uid'] = $this->mid;
    	$map['pubtopic'] = $_POST['pubtopic'];
    	$map['updoc'] = $_POST['updoc'];
    	
    	$options ['userId'] = $this->mid;
    	$options ['max_size'] = 2 * 1024 * 1024; // 2MB
    	$options ['allow_exts'] = 'jpg,gif,png,jpeg,bmp';
    	$info = X ( 'Xattach' )->upload ( 'group_logo', $options );
    	if ($info ['status']) {
    		$map ['logo'] = $info ['info'] [0] ['savepath'] . $info ['info'] [0] ['savename'];
    	} else {
    		$map ['logo'] = 'default.gif';
    	}
    	$map['membercount'] = 1;
    	$map['ctime'] = time();
    	$addRs = D( 'Club' )->add ( $map );
    	if($addRs){
    		$data['clubid'] = $addRs;
    		$data['uid'] = $this->mid;
    		$data['username'] = getUserName( $data['uid'] );
    		$ucUid = M('ucenter_user_link')->where('uid = '.$data['uid'])->getField('uc_uid');
    		$userinfo = get_baseinfo_by_uid($ucUid);
    		if($userinfo['identitytype'] == 3){ //学生
    			$studentinfo = getStudentinfoByUid($ucUid);
    			$data['grade'] = $studentinfo[0]['nj'];
    		}
    		$data['type'] = 1;
    		$data['ctime'] = $map['ctime'];
    		$data['mtime'] = $map['ctime'];
    		if( D('ClubMember')->add($data) ){
				echo 1;	
    		}else{
				echo 0;
    		}
    	}else{
			echo 0;
    	}
    }
    /**
     * 
     +----------------------------------------------------------
     * Enter description here ... （方法功能的注释）
     +----------------------------------------------------------
     * @param <按照参数定义顺序(没有参数的方法就不用该选项)>
     * @return return_type <返回类型(void的方法就不用该选项)>
     * @author RickerYu 2013-12-2
     +----------------------------------------------------------
     */
    public function upload(){
    	$info = D('ClubAttachment')->uploadFile();
    	print_r($info);
    }
    
    /**
     * 删除操作
     * @author lihao
     */
    public function remove()
    {
    	$type = !empty ( $_POST ['type'] ) ? trim ( $_POST ['type'] ) : 'club';
    	$ids  = $_POST ['id'] ? t($_POST ['id']) : '';
    	// 社团，群聊，话题，文件，相册，话题回复
    	if ($type == 'club') {
    		// 社团
    		$res = D ('Club')->remove ($ids);
    	} else if ($type == 'topic') {
    		// 话题
    		$res = D ( 'ClubTopic' )->remove($ids);
    	} else if ($type == 'document') {
    		// 文件
    		$res = D ( 'ClubDocument' )->remove($ids);
    	} else if ($type == 'reply') {
    		//回帖
    		$res = D ( 'ClubReply' )->remove($ids);
    	}
    
    	if ($res) {
    		if (strpos($_POST ['id'], ',')) {
    			echo 1;
    		} else {
    			echo 2;
    		}
    	} else {
    		echo 0;
    	}
    }
    
    /**
     * basic
     * 基础设置管理
     * @access public
     * @return void
     */
    public function indexbasic()
    {
    	if (isset ( $_POST ['editSubmit'] ) == 'do') {
    		array_map ('h', $_POST);
    		$res = model ( 'Xdata' )->lput ( 'group', $_POST );
    		if ($res) {
    			$this->success ( '保存成功' );
    		} else {
    			$this->error ( '保存失败' );
    		}
    	}
    
    	//model('Xdata')->lput('group', $this->GroupSetting->getGroupSetting());
    	$setting = model ( 'Xdata' )->lget ( 'group' );
    
    	//$this->assign ( 'credit_types', X ( 'Credit' )->getCreditType () );
    	$this->assign ( 'setting', $setting );
    
    	//$this->display ();
    }

}
