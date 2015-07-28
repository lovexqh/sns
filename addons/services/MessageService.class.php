<?php
/**
 +------------------------------------------------------------------------------
 * 社区消息服务类
 +------------------------------------------------------------------------------
 * @category	社区消息
 * @package		service
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-11-6 下午4:03:44
 +------------------------------------------------------------------------------
 */
class MessageService extends Service {
	protected $db_prefix;
	protected $ucinfo;
	public function __construct(){
		$this->db_prefix = C ( 'DB_PREFIX' );
		$this->ucinfo = ! isset ( $this->ucInfo ) ? Session::get ( 'ucInfo' ) : $this->ucInfo;
	    //dump($this->ucinfo);
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据uid获取该用户私信
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	下午4:01:16
	 * +----------------------------------------------------------
	 */
	public function getUserMessageList() {
		/*
        $where = 'uc.uc_uid=' . $this->ucinfo['uid']. ' AND l.type=1 and m.member_uid <> c.from_uid';
		$list = M ( '' )->field ( 'uc.uc_uid,m.list_id,m.member_uid,u.uname as getname,l.type,l.title,c.from_uid,c.content,FROM_UNIXTIME(c.mtime)AS messagedate' )->table ( $this->db_prefix . 'message_member as m LEFT JOIN ' . $this->db_prefix . 'message_list as l on m.list_id=l.list_id LEFT JOIN ' . $this->db_prefix . 'message_content as c on m.list_id=c.list_id
left join ' . $this->db_prefix . 'ucenter_user_link as uc on m.member_uid=uc.uid left join '.$this->db_prefix .'user as u on c.from_uid=u.uid ' )->where ( $where )->order ( 'FROM_UNIXTIME(c.mtime) DESC' )->select ();
        echo M('')->getLastSql();
		*/
        $uid = M('ucenter_user_link')->where('uc_uid = '.$this->ucinfo['uid'])->getField('uid');
        $where = 'm.member_uid=' . $uid. '  and m.member_uid <> c.from_uid';
        $list = M ( '' )->field ( 'm.list_id,m.member_uid,l.type,l.title,c.from_uid,c.content,FROM_UNIXTIME(c.mtime)AS messagedate' )->table ( $this->db_prefix . 'message_member as m LEFT JOIN ' . $this->db_prefix . 'message_list as l on m.list_id=l.list_id LEFT JOIN ' . $this->db_prefix . 'message_content as c on m.list_id=c.list_id ' )->where ( $where )->order ( 'FROM_UNIXTIME(c.mtime) DESC' )->select ();
        return $list;
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据uid获取通知
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	下午4:22:37
	 * +----------------------------------------------------------
	 */
	public function getNotifyMessageList() {
        $userInfo = Session::get ( 'userInfo' );
        $list = X ( 'Notify' )->get ( 'receive=' .$userInfo['uid'], 10 );
		// 解析表情
		foreach ( $list ['data'] as $k => $v ) {
			$list ['data'] [$k] ['title'] = preg_replace_callback ( "/\[(.+?)\]/is", replaceEmot, $v ['title'] );
			$list ['data'] [$k] ['body'] = preg_replace_callback ( "/\[(.+?)\]/is", replaceEmot, $v ['body'] );
			$list ['data'] [$k] ['other'] = preg_replace_callback ( "/\[(.+?)\]/is", replaceEmot, $v ['other'] );
		}
		return $list ['data'];
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据uid获取应用消息
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	下午4:24:00
	 * +----------------------------------------------------------
	 */
	public function getAppMessageList() {
		$sql = "SELECT COUNT(appid) AS count,`appid`,`typename` FROM {$this->$db_prefix}myop_myinvite WHERE `touid`={$this->ucinfo['uid']} GROUP BY `appid`";
		$res = M ( '' )->query ( $sql );
		$my_count = array ();
		foreach ( $res as $v ) {
			$my_count [$v ['appid']] = $v;
		}
		
		$map ['touid'] = $this->ucinfo ['uid'];
		$res = M ( 'myop_myinvite' )->where ( $map )->order ( 'appid DESC' )->findPage ( '10' );
		unset ( $map );
		// 将应用消息置为已读
		$appids = getSubByKey ( $res ['data'], 'id' );
		$map ['touid'] = $this->mid;
		M ( 'myop_myinvite' )->where ( $map )->setField ( 'is_read', '1' );
		// 修正邀请链接错误问题
		! defined ( MYOP_URL ) && define ( MYOP_URL, R ( 'home/Message/getmyopurl()' ) );
		
		foreach ( $res ['data'] as $k => $v ) {
			$myml = '';
			$myml = $v ['myml'];
			$myml = str_ireplace ( MYOP_URL, '', $myml );
			$myml = str_ireplace ( 'userapp.php', MYOP_URL . '/userapp.php', $myml );
			$myml = preg_replace ( '/(invite[^\"]*)/', '#', $myml );
			$res ['data'] [$k] ['myml'] = $myml;
		}
		return $res ['data'];
	}
	/**
	 * +----------------------------------------------------------
	 * + 根据uid获取我的教研信息
	 * +----------------------------------------------------------
	 * + @param
	 * + @return	return_type
	 * + @author	曹飞
	 * +----------------------------------------------------------
	 * + 创建时间：	下午12:01:28
	 * +----------------------------------------------------------
	 */
	public function getTaskList()
	{
		$data = array();
		$uid=$this->ucinfo['uid'];
		$classroom = M('Teach' );
		// 检索所有我创建的教研
		$param['uid'] = $uid;
		//$data['create'] = $classroom->where ( $param )->order('meetingid desc')->select();
		// 检索所有我参加过的教研
		$where2 = 'startTime > ' . time ();
		//$data['join'] = $classroom->where ( $where2 )->select();
		$sql2 = " and (c.uid = '$uid' or '".$this->identityID."' in (select identityid from " . $this->db_prefix. "airclass_member where classid=c.classid))";
		//检索所有课堂结束时间晚于现在并且开始时间早于现在的课堂(正进行)
		$where = "stime<=".time()." and etime>".time()."".$sql2."";
		//$data['start'] = D('Airclass','airclass')->lists($where);
		if(empty($data['start'])) unset($data['start']);
	
		//检索所有开始时间晚于现在的课堂(未开始)
		$where = "stime>".time()."".$sql2."";
		//$where = service('ForeAdmin')->getAuditStatus($where, 0, 'airclass');
	
		$data['prepare'] = M('')
		->table($this->db_prefix . "v_airclass as c")
		->field('c.*')
		->where($where)
		->order('c.classid desc')
		->select();
	
		if(empty($data['prepare'])) unset($data['prepare']);
		return $data;
	}
	
	public function getComment(){
		$list = model('GlobalComment')->getUnreadCount($this->mid);
		print_r($list);
		exit();
		foreach ($list['data'] as $key=>$value){
			$list['data'][$key]['uavatar'] = getUserSpace($value['uid'],'null','_blank','{uavatar}');
			$list['data'][$key]['uspace'] = getUserSpace($value['uid'],'null','_blank','{uname}');
			$list['data'][$key]['ctime'] = friendlyDate($value['cTime']);
			$list['data'][$key]['comment'] = formatComment($value['comment']);
			$list['data'][$key]['uname']   = getUserName($value['uid']);
			$list['data'][$key]['del_state']   = ($GLOBALS['ts']['isSystemAdmin'])?2:(($appUid==$this->mid || $value['uid']==$this->mid)?1:0);
			$list['data'][$key]['userGroupIcon']   = getUserGroupIcon($value['uid']);
		}
		exit(json_encode($list));
	}
	// 运行服务，系统服务自动运行
	public function run() {
	}
	
	// 启动服务，未编码
	public function _start() {
		return true;
	}
	
	// 停止服务，未编码
	public function _stop() {
		return true;
	}
	
	// 安装服务，未编码
	public function _install() {
		return true;
	}
	
	// 卸载服务，未编码
	public function _uninstall() {
		return true;
	}
}
?>