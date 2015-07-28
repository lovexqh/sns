<?php
class UserAction extends Action {

    function _initialize() {
        $data ['followTopic'] = D ( 'Follow', 'weibo' )->getTopicList ( $this->mid );
        global $ts;
		//2012/11/6 添加系统应用获取方法
		$this->assign('system_app',$ts['system_app']);
        //SamPeng 2011.12.15重构整个方法
        $this->assign('install_app',$ts['install_apps']);
        $this->assign ( $data );
        $banner = M('ad')->findAll();
        foreach ($banner as &$value) {
            $content = $value['content'];
            $status = unserialize($content);
            if($status != false) {
                $value['content'] = unserialize($content);
            }
        }
        $this->assign('banner',$banner);
        $count = $banner;
        
        //个人资料完整度
        $where_str = "utypeid=0";
    	if(empty($_SESSION['ucInfo']['identitytype'])){
        	$where_str .= ' or utypeid='.intval($_SESSION['ucInfo']['identitytype']);
        }
        $_set_count = M('user_set')->where($where_str)->count();
        $_set_profile = M('user_profile')->where('uid='.$this->mid)->findAll();
        $_set_data = array();
        foreach ($_set_profile as $v) {
        	$_set_data = array_merge($_set_data, unserialize($v['data']));
        }
        $rate = floor((count($_set_data)/$_set_count)*10000)/10000*100;
        $this->assign('userinfo_rate',$rate);
        
        //获取所有学校列表
        $schoollist = uc_get_schoollist();
        $this->assign('schoollist',$schoollist);
    }

    /**
     +----------------------------------------------------------
     * 提到我的页面
     +----------------------------------------------------------
     * @author 小波 2013-6-8
     +----------------------------------------------------------
     */
    public function atme() {
    	model ( 'UserCount' )->setZero ( $this->mid, 'atme' );
    	$data ['list'] = D ( 'Operate', 'weibo' )->getAtme ( $this->mid );
    	// 同步的设置
    	$bind = M ( 'login' )->where ( 'uid=' . $this->mid )->findAll ();
    	foreach ( $bind as $v ) {
    		$data ['login_bind'] [$v ['type']] = $v ['is_sync'];
    	}
    	$this->assign ( $data );
    	$this->setTitle ( L('at_me_weibo') );
    	$this->display ( 'index' );
    }

    /**
     +----------------------------------------------------------
     * 我的微博收藏
     +----------------------------------------------------------
     * @author 小波 2013-6-8
     +----------------------------------------------------------
     */
	function collection() {
        $data ['list'] = D ( 'Operate', 'weibo' )->getCollection ( $this->mid );

        // 同步的设置
        $bind = M ( 'login' )->where ( 'uid=' . $this->mid )->findAll ();
        foreach ( $bind as $v ) {
            $data ['login_bind'] [$v ['type']] = $v ['is_sync'];
        }

        $this->assign ( $data );
        $this->setTitle (L('my_fav'));
        $this->display ( 'index' );
    }
	
    /**
     +----------------------------------------------------------
     * 评论列表
     +----------------------------------------------------------
     * @author 小波 2013-6-8
     +----------------------------------------------------------
     */
    function comments() {
        $data ['type'] = ($_GET ['type'] == 'send') ? 'send' : 'receive';
        $data ['from_app'] = ($_GET ['from_app'] == 'other') ? 'other' : 'weibo';

        // 优先展示微博，优先展示有未读from_app
        if (model ( 'UserCount' )->getUnreadCount ( $this->mid, 'comment' ) <= 0 && model ( 'GlobalComment' )->getUnreadCount ( $this->mid ) > 0)
            $data ['from_app'] = 'other';

        if ($data ['from_app'] == 'weibo') {
            $data ['type'] == 'receive' && model ( 'UserCount' )->setZero ( $this->mid, 'comment' );

            //$data['person'] = (in_array( $_GET['person'] , array('all','follow','other')) )?$_GET['person']:'all';
            $data ['person'] = 'all';
            $data ['list'] = D ( 'Comment', 'weibo' )->getCommentList ( $data ['type'], $data ['person'], $this->mid );
        } else {
            $dao = model ( 'GlobalComment' );
            $data ['type'] == 'receive' && $dao->setUnreadCountToZero ( $this->mid );

            $data ['person'] = 'all';
            $data ['list'] = $dao->getCommentList ( $data ['type'], $this->mid );

            /*
             * 缓存评论发表者, 被回复的用户,
             */
            $ids = getSubBeKeyArray ( $data ['list'] ['data'], 'appuid,uid,to_uid' );
            D ( 'User', 'home' )->setUserObjectCache ( array_unique ( array_merge ( $ids ['appuid'], $ids ['uid'], $ids ['to_uid'] ) ) );

            foreach ( $data ['list'] ['data'] as $k => $v )
                $data ['list'] ['data'] [$k] ['data'] = unserialize ( $v ['data'] );
        }

        $this->assign ( 'userCount', X ( 'Notify' )->getCount ( $this->mid ) );

        $this->assign ( $data );
        $this->setTitle ( $data ['type'] == 'receive' ? L('receive_comment') : L('send_comment') );
        $this->display ();
    }

    /**
     +----------------------------------------------------------
     * 获取搜索时的关键字，以及分页时关键字的保存及删除（重构）
     +----------------------------------------------------------
     * @param string $key 要返回的键
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-16 下午2:48:25
     +----------------------------------------------------------
     */
    private function __getSearchKey($k) {
        $key = '';
        // 为使搜索条件在分页时也有效，将搜索条件记录到SESSION中
        if ((isset ( $_REQUEST ['k'] ) && ! empty ( $_REQUEST ['k'] )) || 
        	('' != $_REQUEST['sex'] || 
        	 '' != $_REQUEST['identity'] || 
        	 '' != $_REQUEST['school'] || 
        	 '' != $_REQUEST['class']))
         {
            if ($_GET ['k']) {
                $key = html_entity_decode ( urldecode ( $_GET ['k'] ), ENT_QUOTES );
            } elseif ($_POST ['k']) {
                $key = $_POST ['k'];
            }
            // 关键字不能超过200个字符
            if (mb_strlen ( $key, 'UTF8' ) > 200)
                $key = mb_substr ( $key, 0, 200, 'UTF8' );
            if (!empty($k) && 'all' == $k) {
            	$_SESSION ['home_user_search_key'] = serialize ( $_POST );
            	$key = $_POST;
            }else {
            	$_SESSION ['home_user_search_key'] = serialize ( $key );
            }
        } else if (is_numeric ( $_GET [C ( 'VAR_PAGE' )] )) {
        		$key = unserialize ( $_SESSION ['home_user_search_key'] );
        } else {
            unset($_SESSION['home_user_search_key']);
        }
        if (empty($k)) {
        	$key = trim(str_replace(array('%','\'','"','<','>'),'',$key));
        }
        return  $key;
    }

    private function __checkSearchPopedom() {
        //游客搜索限制
        if ($this->mid <= 0 && intval ( model ( 'Xdata' )->get ( 'siteopt:site_anonymous_search' ) ) <= 0)
            redirect ( U ( 'home/User/index' ) );

        //搜索间隔限制,不能频繁使用不相同的关键词去搜索
        $lock_key = 'search_lock_'.ACTION_NAME.'_'.t($_GET['type']);
        $max_search_time = intval($GLOBALS['ts']['site']['max_search_time']);
        if($max_search_time >0 && isset($_SESSION[$lock_key]) && ($_SESSION[$lock_key]+$max_search_time) > time() && intval($_GET['p'])<=1){
            send_http_header('utf8');
            $this->error('不要频繁搜索,请'.$max_search_time.'秒后再试!');
        }else{
            $_SESSION[$lock_key] = time();
        }
    }

    /**
     +----------------------------------------------------------
     * 专题页(新微博页面中)
     +----------------------------------------------------------
     * @author 小波 2013-7-2
     +----------------------------------------------------------
     */
    public function topics()
    {
        //$this->__checkSearchPopedom ();
        $data['search_key'] = $this->__getSearchKey ();
        Session::pause();
        // 专题信息
        if (false == $data['topics'] = D('Topics', 'weibo')->getTopics($data['search_key'], $_GET['id'], $_GET['domain'], 1)) {
            if (null == $data['search_key']) {
                $this->error(L('special_not_exist'));
            }
            $data['topics']['name'] = t($data['search_key']);
        }

        $data['search_key'] = $data['search_key'] ? $data['search_key'] : html_entity_decode($data['topics']['name'], ENT_QUOTES);
        $data['search_key_id'] = $data['topics']['topic_id'] ? $data['topics']['topic_id'] : D('Topic', 'weibo')->getTopicId($data['search_key']);

        $data['followState'] = D ('Follow', 'weibo')->getTopicState ($this->mid, $data['search_key']);
        // 其他关注该话题的人
        $data['other_following'] = D('Follow', 'weibo')->field('uid')
                                    ->where("uid<>{$this->mid} AND fid={$data['search_key_id']} AND type=1")
                                    ->limit(9)->findAll();
        // 微广播列表
        $data['type'] = h ( $_GET ['type'] );
        $data['list'] = D ( 'Operate', 'weibo' )->doSearchWithTopic ( "#{$data['topics']['name']}#", $data ['type'], $this->mid);
//      $data['list'] = D ( 'Operate', 'weibo' )->doSearch ( "#{$data['topics']['name']}#", $data ['type'] );
//      $data['list']['count'] = D ( 'Operate', 'weibo' )->where("content LIKE '%#{$data['topics']['name']}#%' AND isdel=0")->count();

        // 微广播Tab
        $data['weibo_menu'] = array(
                                ''  => L('all'),
                                'original' => L('original'),
                              );
        Addons::hook('home_index_weibo_tab', array(&$data['weibo_menu']));

        $this->setTitle ( L('special').$data ['search_key']);
        $data['search_key'] = h(t($data['search_key']));

        $this->assign ( $data );
        $this->display();
    }

    // 查找话题
    public function search() {

        $this->__checkSearchPopedom ();
        $data ['search_key'] = $this->__getSearchKey ();
        Session::pause();
        $data ['followState'] = D ( 'Follow', 'weibo' )->getTopicState ( $this->mid, $data ['search_key'] );
        $data ['type'] = t ( $_REQUEST ['type'] );
        $data ['list'] = D ( 'Operate', 'weibo' )->doSearch ( $data ['search_key'], $data ['type'] );
        $data ['followTopic'] = D ( 'Follow', 'weibo' )->getTopicList ( $this->mid );
        $data ['search_key_id'] = D ( 'Topic', 'weibo' )->getTopicId ( $data ['search_key'] );
        $data ['search_key'] = h ( t ( $data ['search_key'] ) );
        // 微广播Tab
        $data['weibo_menu'] = array(
                                        ''  => L('all'),
                                'original' => L('original'),
                              );
        Addons::hook('home_index_weibo_tab', array(&$data['weibo_menu']));
        $data['weibo_menu'] = array(''  => L('all'), 'location' => L('local'), 'follow' => L('attention')) + $data['weibo_menu'];
        Addons::hook('home_search_weibo_tab', array(&$data['weibo_menu']));

        $this->assign ( $data );
        $this->setTitle ( L('search_weibo').$data['search_key'] );
        $this->display ();
    }

    /**
     +----------------------------------------------------------
     * 查找用户 （重构后可以加入多条件查询）
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-15 下午2:21:25
     +----------------------------------------------------------
     */
    public function searchuser() {
        $this->__checkSearchPopedom ();
        $param = $this->__getSearchKey ('all');
        $data ['search_key'] = $param['key'] = $param['k'];
        Session::pause();
        //获取参数
        $data ['list'] = D ( 'Follow', 'weibo' )->doSearchUser ( $param );
        
        $data ['followTopic'] = D ( 'Follow', 'weibo' )->getTopicList ( $this->mid );
        $data ['search_key'] = h ( t ( $data ['search_key'] ) );
        //判断是否选择学校
        if($_POST['school']!='' && $_POST['school']>0){
        	$classlist = uc_get_classllist_by_sid(intval($_POST['school']));
        	$data['classlist'] = $classlist;
        }
        $data = array_merge($data,$_POST);
        $this->assign ( $data );
        $this->setTitle ( L('search_people').$data['search_key'] );
        $this->display ();
    }

    //查找我关注的
    public function searchTips()
    {
        if ($this->mid <= 0)
            redirect ( U ( 'home/User/index' ) );

        $key = str_replace('_', '\_', h ( $_GET ['key'] ));
        $db_prefix  =  C('DB_PREFIX');
         Session::pause();
        //$list = M ( 'user' )->field('uname')->where ( "uname LIKE '%{$key}%'" )->order ( "LOCATE('{$key}', uname) ASC" )->limit ( 10 )->findAll();
        $list = M('')->field('u.*')->field('u.uname')
                     ->table("{$db_prefix}weibo_follow AS f LEFT JOIN {$db_prefix}user AS u ON f.uid={$this->mid} AND f.fid=u.uid")
                     ->where("u.uname LIKE '%{$key}%'")
                     ->order ( "LOCATE('{$key}', u.uname) ASC" )
                     ->limit ( 10 )->findAll();
        if ($list) {
            exit ( json_encode ( $list ) );
        } else {
            echo '';
        }
    }

    //查找Tag
    public function searchtag() {
        $this->__checkSearchPopedom ();
        $data ['search_key'] = $this->__getSearchKey ();
         Session::pause();
        $data ['list'] = D ( 'UserTag' )->doSearchTag ( $data ['search_key'] );
        $data ['followTopic'] = D ( 'Follow', 'weibo' )->getTopicList ( $this->mid );
        $data ['search_key'] = h ( t ( $data ['search_key'] ) );
        $this->assign ( $data );
        $this->setTitle ( L('search_tag').$data ['search_key']);
        $this->display ();
    }

	//找人 -  2011-11-28 优化 解决推荐用户中还有已关注用户问题
    function findfriend() {
        Session::pause();
        $type_array = array ('followers', 'hot', 'understanding', 'newjoin' );
        $data ['type'] = in_array ( $_GET ['type'], $type_array ) ? $_GET ['type'] : 'newjoin';
        $user_model = D ( 'User', 'home' );

        $db_prefix = C ( 'DB_PREFIX' );
        switch ($data ['type']) {
            case 'followers' :
                $data ['list'] = M ("weibo_follow")->where("fid!=$this->mid AND fid not in ( select fid from ".C('DB_PREFIX')."weibo_follow where uid=$this->mid) ")
											->field('fid as uid,count(uid) as count')
											->group("fid")
											->order('`count` DESC')
											->limit(10)
											->findAll();
                //$data ['list'] = D ( 'Follow', 'weibo' )->getTopFollowerUser ();

                $uids = getSubByKey ( $data ['list'], 'uid' );

                $user_model = D ( 'User', 'home' );
                $user_count_model = model ( 'UserCount' );
                $user_model->setUserObjectCache ( $uids );
                $user_count_model->setUserFollowerCount ( $uids );
                foreach ( $data ['list'] as $key => $value ) {
                    $data ['list'] [$key] = $user_model->getUserByIdentifier ( $value ['uid'] );
                    $data ['list'] [$key] ['follower'] = $user_count_model->getUserFollowerCount ( $value ['uid'] );
                }
                break;

            case 'hot' :

				$data ['list'] = M ("weibo")->where("a.uid!=$this->mid AND a.uid not in ( select fid from ".C('DB_PREFIX')."weibo_follow as b where b.uid=$this->mid) ")
											->field('a.uid,count(a.weibo_id) as weibo_num')
											->table(C('DB_PREFIX').'weibo as a')
											->group("uid")
											->order('weibo_num DESC')
											->limit(10)
											->findAll();

				//$data ['list'] = M ("weibo")->query ( "SELECT uid,count(weibo_id) as weibo_num FROM {$db_prefix}weibo GROUP BY uid ORDER by weibo_num DESC LIMIT 10" );

				$uids = getSubByKey ( $data ['list'], 'uid' );

                $user_model = D ( 'User', 'home' );
                $user_count_model = model ( 'UserCount' );
                $user_model->setUserObjectCache ( $uids );
                $user_count_model->setUserFollowerCount ( $uids );
                foreach ( $data ['list'] as $key => $value ) {
                    $data ['list'] [$key] = $user_model->getUserByIdentifier ( $value ['uid'] );
                    $data ['list'] [$key] ['follower'] = $user_count_model->getUserFollowerCount ( $value ['uid'] );
                    $data ['list'] [$key] ['weibo_num'] = $value ['weibo_num'];
                }
                break;

            case 'understanding' :
                $data ['list'] = model ( 'Friend' )->getRelatedUser ( $this->mid, $max = 10 );
                $uids = getSubByKey ( $data ['list'], 'uid' );

                $user_model = D ( 'User', 'home' );
                $user_count_model = model ( 'UserCount' );
                $user_model->setUserObjectCache ( $uids );
                $user_count_model->setUserFollowerCount ( $uids );
                foreach ( $data ['list'] as $key => $value ) {
                    $data ['list'] [$key] = $user_model->getUserByIdentifier ( $value ['uid'] );
                    $data ['list'] [$key] ['follower'] = $user_count_model->getUserFollowerCount ( $value ['uid'] );
                }
                break;

            case 'newjoin' :
                $data ['list'] = M ("user")->where("a.is_active=1 AND a.is_init=1 AND a.uid!={$this->mid} AND a.uid not in (SELECT fid FROM ".C('DB_PREFIX')."weibo_follow as b WHERE b.uid={$this->mid}) ")
											->field('a.uid,a.uname,a.domain,a.location,a.ctime')
											->table(C('DB_PREFIX').'user as a')
											->order('a.uid DESC')
											->limit(10)
											->findAll();

                D ( 'User', 'home' )->setUserObjectCache ( $data ['list'] );
                $dao = model ( 'UserCount' );
                $dao->setUserFollowerCount ( getSubByKey ( $data ['list'], 'uid' ) );
                foreach ( $data ['list'] as $key => $value )
                    $data ['list'] [$key] ['follower'] = $dao->getUserFollowerCount ( $value ['uid'] );
                break;
        }

        // 被关注的排行榜
        $data ['topfollow'] = D ( 'Follow', 'weibo' )->getTopFollowerUser ();
        D ( 'User', 'home' )->setUserObjectCache ( getSubByKey ( $data ['topfollow'], 'uid' ) );

        $this->assign ( $data );
        $this->setTitle ( L('find_people') );
        $this->display ();
    }

    //表情
    function emotions() {
         Session::pause();
        exit ( json_encode ( model ( 'Expression' )->getAllExpression () ) );
    }

    //获取统计数据
    function countNew() {
         Session::pause();
        exit ( json_encode ( X ( 'Notify' )->getCount ( $this->mid ) ) );
    }

    // 删除动态
    public function doDeleteMini() {
        echo X ( 'Feed' )->deleteOneFeed ( $this->mid, intval ( $_POST ['id'] ) ) ? '1' : '0';
    }

    public function closeAnnouncement() {
        $announcement_ctime = model ( 'Xdata' )->getField ( 'mtime', '`list`="announcement"' );
        $announcement_ctime = strtotime ( $announcement_ctime );
        cookie ( "announcement_closed_{$this->mid}", $announcement_ctime );
    }

    private function __getLoginBind()
    {
        $bind = M ( 'login' )->where ( 'uid=' . $this->mid )->findAll ();
        $result = array();
        foreach ( $bind as $v ) {
            $result[$v ['type']] = $v ['is_sync'];
        }
        return $result;
    }

    private function __getDynamic($type)
    {
        $data['list'] = X ( 'Feed' )->get ( $this->mid );
        return $data;
    }

    private function __setAnnouncement ()
    {
        // 公告
        if (($announcement = F ( '_home_user_action_announcement' )) === false) {
            $announcement = model ( 'Xdata' )->where ( '`list`="announcement"' )->findAll ();
            foreach ( $announcement as $v ) {
                $announcement [$v ['key']] = unserialize ( $v ['value'] );
            }
            $announcement ['ctime'] = strtotime ( $announcement ['0'] ['mtime'] );

            F ( '_home_user_action_announcement', $announcement );
        }

        if (cookie ( "announcement_closed_{$this->mid}" ) != $announcement ['ctime'])
            $this->assign ( 'announcement', $announcement );
    }

    private function __getWeiboList($install_app,$index_type,$weibo_type,$simple=0)
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
                $data ['list'] = D('Operate', 'weibo')->getHomeList ( $this->mid, $weibo_type, '', 5, $data ['follow_gid'],$simple);
                break;
            case self::INDEX_TYPE_GROUP:
                $data ['list'] = D('WeiboOperate','group')->getHomeList($this->mid, $data['gid'], '', 5);
                break;
            case self::INDEX_TYPE_ALL:
                $order = 'weibo_id DESC';
                $data['list']  = D('Operate','weibo')->doSearchTopic("",$order,$this->mid);
                break;
            default:
                $data ['list'] = D('Operate', 'weibo')->getHomeList ( $this->mid, $weibo_type, '', 5, $data ['follow_gid'] );
        }

		if($data['list']['data']){
			// 最新一条微广播的Id (countNew时使用)
			$_last_weibo = reset($data ['list'] ['data']);
			$data ['lastId'] = $_last_weibo['weibo_id'];
			$_since_weibo = end($data ['list'] ['data']);
			$data['sinceId'] = $_since_weibo['weibo_id'];
		}
        return $data;
    }

    private function __paramUserFollowGroup($type){
        $data ['follow_gid'] = is_numeric ( $_GET ['follow_gid'] ) ? $_GET ['follow_gid'] : 'all';
        $group_list = D ( 'FollowGroup', 'weibo' )->getGroupList ( $this->uid );
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

    //查询用户操作 - 用于SearchUserWidgets
    public function dosearch() {
        $map['email'] = array('LIKE', '%'.$_REQUEST['q'].'%');
        $field = 'uid, email, uname';
        $limit = $_REQUEST['limit'];
        $list = M('user')->field($field)->where($map)->limit($limit)->findAll();
        $list = empty($list) ? array() : $list;

        exit(json_encode($list));
    }

    // 附件审核管理员 审核页面
    public function auditAttach() {
        // 获取审核管理员的人数
        $data = model('Xdata')->get('audit:attach_auditing');
        $uids = explode(',', $data['attach_auditing_uid']);
        $uids = array_unique($uids);
        $uids = array_filter($uids);
        $count = count($uids);

        $map['status'] = 0;
        foreach($uids as $key => $value) {
            if($value == $this->mid) {
                $map['_string'] = 'id % '.$count.' = '.$key;
            }
        }

        $dao = model('Attach');
        $attaches = $dao->getAttachByMap($map);
        $extensions = $dao->enumerateExtension();
        $this->assign($attaches);
        $this->assign('extensions', $extensions);

        $this->display();
    }

    // 审核附件通过操作
    public function doauditAttach() {
        $ids = t($_POST['ids']);
        $ids = explode(',', $ids);
        $ids = array_filter($ids);
        $map['id'] = array('IN', $ids);
        $save['status'] = 1;
        $res = M('attach')->where($map)->save($save);
        // 添加附件记录
        foreach($ids as $value) {
            $add['attach_id'] = $value;
            $add['uid'] = $this->mid;
            $add['type'] = 1;
            $add['ctime'] = time();
            M('audit_attach')->add($add);
        }
        echo $res;
    }

    // 审核附件不通过操作
    public function dounAuditAttach() {
        $ids = t($_POST['ids']);
        $ids = explode(',', $ids);
        $ids = array_filter($ids);
        $map['id'] = array('IN', $ids);


        // 将图片覆盖为默认图片
        $attachInfo = M('attach')->where($map)->findAll();
        $defaultImage = APPS_PATH.'/admin/Tpl/default/Public/unAudit.jpg';
        $imageArr = array('jpg', 'gif', 'png', 'jpeg', 'bmp');

        foreach($attachInfo as $value) {

            $savename = $value['savename'];
            $targetPath = UPLOAD_PATH.'/'.$value['savepath'];

            rename($targetPath.$savename,$targetPath.'old_'.$savename);

            if(in_array($value['extension'], $imageArr)) {
                @copy($defaultImage, $targetPath.$savename);
            }

            //插入数据
            $dmap = array();
            $dmap['id'] = $value['id'];
            M('attach')->where($dmap)->limit(1)->delete();
            D('attach_back')->add($value);
        }

        // 添加附件记录
        foreach($ids as $value) {
            $add['attach_id'] = $value;
            $add['uid'] = $this->mid;
            $add['type'] = 2;
            $add['ctime'] = time();
            M('audit_attach')->add($add);
        }
        echo 1;
    }

    /*获取更多微广播，关注信息*/
    public function getWeiboList(){
    	$count = intval($_POST['count']);
    	if($count){
			$data = D('Operate', 'weibo')->getWeiboDyna($this->mid,0,"$count,10");
    	}
    	foreach($data as &$obj){
    		$obj['face'] = getUserFace($obj['uid'],'m');
    		$obj['name'] = getUserName($obj['uid']);
    		$obj['time'] = date('m月d日 H:i:s',$obj['ctime']);
    		$obj['content'] = format(formatUrl($obj['content']));
    	}
    	echo json_encode($data);
    }
    
    /**
     +----------------------------------------------------------
     * 获取某学校下的班级信息
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-14 下午1:44:44
     +----------------------------------------------------------
     */
    public function getClassList(){
    	$schoolid = intval($_POST['sid']);
    	$nj =  intval($_POST['nj']);
    	$xd =  intval($_POST['xd']);
    	if ($schoolid>0) {
    		if ($xd==0 && $nj==0){
    			$classlist = uc_get_classllist_by_sid($schoolid);
    		}else{
    			$classlist = uc_get_gradclass_by_id($nj,$schoolid,$xd);
    		}
    	}
    	echo json_encode($classlist);
    }
    
    /**
     +----------------------------------------------------------
     * 页面级ajax调用获取学校信息
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-23 下午4:08:18
     +----------------------------------------------------------
     */
    public function getSchoolList(){
    	$xd = $_REQUEST['xd'];
    	switch (intval($xd)) {
    		case 1:
    			//获取所有小学
    			$list = uc_get_small_school();
    			break;
    		case 2:
    			//获取所有初中
    			$list = uc_get_middle_school();
    			break;
    		case 3:
    			//获取所有高中
    			$list = uc_get_high_school();
    			break;
    		default:
    			//获取所有学校
    			$list = uc_get_all_school();
    	
    	}
    	echo json_encode($list);
    }
    
    /**
     +----------------------------------------------------------
     * 页面级ajax调用获取年级信息
     +----------------------------------------------------------
     * @author 小波
     +----------------------------------------------------------
     * 创建时间：2013-3-23 下午4:08:18
     +----------------------------------------------------------
     */
    public function getGradeList(){
    	$xx = $_POST['xx'];
    	$xd = $_POST['xd'];
    	$list = uc_get_grades_by_sid(intval($xx),intval($xd));
    	echo json_encode($list);
    }

    /**
     *
    * @Title: canlendar
    * @Description: 日历显示操作
    * @return
    * @author Ricker lhyfe@sohu.com
     */
    public function canlendar(){
    	$canlendarlist = D('User', 'home')->getCourseByUid($this->mid);
    	//print_r($canlendarlist) ;
    	print_r(json_encode($canlendarlist));
    }
    
    /**
     +----------------------------------------------------------
     * 退出大桌面系统
     +----------------------------------------------------------
     * @author 小波 2013-6-7
     +----------------------------------------------------------
     */
    public function outdsk(){
    	//孙晓波 2013-05-27进行修改(功能:退出大桌面系统) start
    	unset($_SESSION['system']);
    	//孙晓波 2013-05-27进行修改(功能:退出大桌面系统) start
    	redirect(U("home/User/index"));
    }

}
?>