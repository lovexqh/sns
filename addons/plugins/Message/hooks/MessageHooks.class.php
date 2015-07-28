<?php
class MessageHooks extends Hooks
{
    protected $default;
    protected $group;
    protected $identityID;
    protected $db_prefix;
    /**
     +----------------------------------------------------------
     + 构造函数
     +----------------------------------------------------------
     + @param	
     + @return	return_type
     + @author	小波 (Administrator)
     +----------------------------------------------------------
     + 创建时间：	2013-11-7 下午12:57:24
     +----------------------------------------------------------
     */
    public function __construct()
    {
        parent::__construct();
        $this->db_prefix= C('DB_PREFIX');
        $this->ucInfo = !isset($this->ucInfo) ? Session::get('ucInfo') : $this->ucInfo;
        $this->identityID = $this->ucInfo['identityid'];
        $this->init();
    }
    /**
     +----------------------------------------------------------
     + 该类初始化方法
     +----------------------------------------------------------
     + @param	
     + @return	return_type
     + @author	小波 (Administrator)
     +----------------------------------------------------------
     + 创建时间：	2013-11-7 下午12:52:44
     +----------------------------------------------------------
     */
	public function init()
	{
	    //默认面板
	    $this->default = 'message1';
	}
	/**
	 +----------------------------------------------------------
	 + 默认插件首页
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-7 上午10:46:16
	 +----------------------------------------------------------
	 */
	public function desktop_user_remind_show(){
		echo '<link type="text/css" href="' . $this->htmlPath . '/html/css/remind.css" rel="stylesheet">';

		$data['maxmenu'] = $this->_getMenuGroup();

		$data['default'] = $this->default;
		$this->assign($data);
	    $this->display('remind');
	}
	/**
	 +----------------------------------------------------------
	 + 获取面板上的数据
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-7 下午12:46:14
	 +----------------------------------------------------------
	 */
	public function getPanelData() {
		$panel = empty ( $_POST ['panel'] ) ? $this->default : $_POST ['panel'];

		if($panel != 'message1'){
			// 获取二级菜单
			$this->group = $this->_getMenuGroup ( $panel );
			$this->assign ( $panel . 'menu', $this->group );
			// 获取详细数据
			$method = "_get" . ucfirst ( $panel ) . "Data";
			$this->$method ();
		}
	}
	/**
	 +----------------------------------------------------------
	 + 获取每个模块的菜单信息
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-7 上午10:47:38
	 +----------------------------------------------------------
	 */
	private function _getMenuGroup($type='all'){
	    //判断是否有当前的UIA数据信息
	    $this->ucInfo = !isset($this->ucInfo) ? Session::get('ucInfo') : $this->ucInfo;
	    switch ($type){
	    	case 'message':    //我的消息
	    	    //$menu = array('message'=>'我的私信','notify'=>'系统通知','pinglun'=>'我的评论','tdwode'=>'提到我的');
				$menu = array('message'=>'我的私信','notify'=>'系统通知');
	    	    break;
	    	default:    //全部大分类
	    	    $menu = array(
	    	        'message'=> array(
	    	                'key'=>'message',
	    	                'title'=>'我的消息',
	    	                'width'=>'450',
	    	                'height'=>'320',
	    	            )
	    	    );
	    	    $typelist = array(2,3,4);
	    	    if (!in_array($this->ucInfo['identitytype'], $typelist)) {
	    	    	//unset($menu['exercise']);
	    	    	//unset($menu['schedule']);
	    	    }
	    }
	    return $menu;
	}
	/**
	 +----------------------------------------------------------
	 + 获取消息面板数据
	 +----------------------------------------------------------
	 + @param	
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-7 下午12:43:36
	 +----------------------------------------------------------
	 */
	public function _getMessageData(){
		$panel = 'message';
		//获取二级菜单
		$data['group']= $this->_getMenuGroup($panel);
		$list=service('Message');
		//循环获取二级分组内数据
		foreach ($data['group'] as $key=>$val){
		    switch ($key){
		    	case 'message':    //我的私信
	    	        $data['message'] =$list->getUserMessageList();
	    	        break;
    	        case 'notify':    //系统通知
    	            $data['notify'] = $list->getNotifyMessageList();
    	            break;	            
	            case 'pinglun':
	            	break;
	            case 'tdwode':
	            	break;
		    }
		}
		//设置默认显示tab
		if (count($data['task'])) {
			$data['isdefault'] = 'task';
		}else{
		    $data['isdefault'] = 'message';
		}
        //dump($data);
		$this->assign($data);
	    $this->display('message');
	}
	
	/**
	 +----------------------------------------------------------
	 + 获取课程面板数据
	 +----------------------------------------------------------
	 + @param
	 + @return	return_type
	 + @author	小波 (Administrator)
	 +----------------------------------------------------------
	 + 创建时间：	2013-11-7 下午12:43:36
	 +----------------------------------------------------------
	 */
	public function _getScheduleData() {
		// 获取二级菜单
		$panel = 'schedule';
		$group = $this->_getMenuGroup ( $panel );
		$data ['group'] = $this->_getMenuGroup ( $panel );
		
		foreach ($data['group'] as $key=>$val){
		    switch ($key){
		    	case 'day':    //我的私信
	    	        $list=D('Course','teaching');
					$identitytype = $this->ucInfo['identitytype'];
					$uc_uid = $this->ucInfo['uid'];
					//$headmaster='-1';
					switch ($identitytype)
					{
						case '2':
							$data['type'] = 'teacher';
							$list=$list->getTeacherCourse($uc_uid);
							break;
						case '3':
							$data['type'] = 'student';
							$list=$list->getStudentCourse($uc_uid);
							break;//学生
					}
					$date = date('Y-m-d',time());
					for($i=0;$i<3;$i++){
						$datear[$i] = format_datetomd(add_date($date,$i));
					}
					$weeknum = D('Course','teaching')->datetoweek($date);
					$thiswk = date("w",time()) == 0 ? 7 : date("w",time());
					//print_r($list);
					if(is_array($list)){
						$list = $list[$weeknum];
					}else{
						$list = array();
					}
											
	    	        break;    	        
		    }
		}		
		$data['isdefault'] = 'day';
 		$this->assign('datear',$datear);
 		$this->assign('thiswk',$thiswk);
 		$this->assign('list',$list);
 		$this->assign ($data);
		$this->display ( 'schedule' );
	}
	
	
}