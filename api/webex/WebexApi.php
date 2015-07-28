<?php
/**
 * 
 * webex的API
 * @author admin
 *
 */
class WebexApi{
	

	/**
	 * +----------------------------------------------------------
	 * 提交array,返回json
	 * +----------------------------------------------------------
	 * @param array $data 
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	private function postIt($data){
		$ch = curl_init();  
		/*在这里需要注意的是，要提交的数据不能是二维数组或者更高 */
		
		curl_setopt($ch, CURLOPT_URL, "http://openapi.educomm.cn/index.php?r=WebexApi/Route"); 
		curl_setopt($ch, CURLOPT_POST, 1);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果把这行注释掉的话，就会直接输出  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  
		$result = curl_exec($ch);  
		$brr = (array)(json_decode($result)); 
		return $brr;
		curl_close($ch);  
		
	}
	/**
	 * +----------------------------------------------------------
	 * 创建webex
	 * +----------------------------------------------------------
	 * @param string $name 名称
	 * @param string $pwd 密码
	 * @param string $stime 开始时间
	 * @param string $last 持续分钟
	 * $param return array('status','reason','meetingkey','hosturl','attendeeurl')
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function createMeeting($schoolid,$app,$appid,$uid,$teachname,$password,$stime,$last){
		$data = array(
			'domain'	=>	$_SERVER['HTTP_HOST'],
			'schoolid'	=>	$schoolid,
			'app'		=>	$app,
			'appid'		=>	$appid,
			'uid'		=>	$uid,
			'teachname'	=>	$teachname,
			'teachpwd'	=>	$password,
			'stime'		=>	$stime,
			'last'		=>	$last,
			'action'	=>	'create'		
		);
        //print_r($data);
		$obj = $this->postIt($data);
		return $obj;
	}
	/**
	 * +----------------------------------------------------------
	 * 查询会议是否结束
	 * +----------------------------------------------------------
	 * @param string $username 用户名称
	 * @param string $wbxpwd 会议密码
	 * @param string $stime 开始时间
	 * @param string $useremail 用户邮件
	 * @param string $stime 开始时间
	 * @param string $stime 持续时间
	 * @param string $content 要查询的内容(主持人URL:hosturl,参加人URL:attendeeurl,会议是否结束:meetingend)
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function getHostUrl($meetingkey,$username){
		$data = array(
			'meetingkey'=>	$meetingkey,		
			'username'	=>	$username,
			'action'	=>	'hosturl'		
		);
		$obj = $this->postIt($data);
		return $obj;
	}
	/**
	 * +----------------------------------------------------------
	 * 查询webex,判断是否结束(status=1为不结束,status=0为结束)
	 * +----------------------------------------------------------
	 * @param string $username 用户名称
	 * @param string $wbxpwd 会议密码
	 * @param string $stime 开始时间
	 * @param string $useremail 用户邮件
	 * @param string $stime 开始时间
	 * @param string $stime 持续时间
	 * @param string $content 要查询的内容(主持人URL:hosturl,参加人URL:attendeeurl,会议是否结束:meetingend)
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function ifgetHostUrl($meetingkey){
		$data = array(
			'meetingkey'=>	$meetingkey,
			'action'	=>	'ifend'		
		);
		$obj = $this->postIt($data);
		return $obj;
	}
	/**
	 * +----------------------------------------------------------
	 * 查询webex,获取attendeeurl
	 * +----------------------------------------------------------
	 * @param string $username 用户名称
	 * @param string $wbxpwd 会议密码
	 * @param string $stime 开始时间
	 * @param string $useremail 用户邮件
	 * @param string $stime 开始时间
	 * @param string $stime 持续时间
	 * @param string $content 要查询的内容(主持人URL:hosturl,参加人URL:attendeeurl,会议是否结束:meetingend)
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function getAttendeeUrl($meetingkey,$username,$wbxpwd,$useremail){
		$data = array(
			'meetingkey'=>	$meetingkey,		
			'username'	=>	$username,
			'wbxpwd'	=>	$wbxpwd,
			'email'		=>	$useremail,
			'action'	=>	'attendeeurl'		
		);
		$obj = $this->postIt($data);
		return $obj;
	}
	/**
	 * +----------------------------------------------------------
	 * 更新webex
	 * +----------------------------------------------------------
	 * @param string $name 名称
	 * @param string $pwd 密码
	 * @param string $stime 开始时间 不是时间戳
	 * @param string $last 持续分钟
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function updateMeeting($meetingkey,$teachname,$password,$stime,$last,$oldstime){
		$data = array(
			'meetingkey'=>	$meetingkey,
			'teachname'	=>	$teachname,
			'teachpwd'	=>	$password,
			'stime'		=>	$stime,
			'last'		=>	$last,
			'oldtime'	=>	$oldstime,
			'action'	=>	'update'		
		);
		$obj = $this->postIt($data);
		return $obj;
	}
	/**
	 * +----------------------------------------------------------
	 * 删除webex
	 * +----------------------------------------------------------
	 * @param string $meetingkey 
	 * @param string $stime 这个会议的开始时间
	 * @author 小伟2013-3-4
	 * +----------------------------------------------------------
	 */
	public function delMeeting($meetingkey,$stime){
		$data = array(
			'meetingkey'=>	$meetingkey,
			'stime'		=>	$stime,
			'action'	=>	'delete'		
		);
		$obj = $this->postIt($data);
		return $obj;
	}


    public function lstwebex($stime){
        $data = array(
            'stime'		=>	$stime,
            'action'	=>	'lstwebex'
        );
        $obj = $this->postIt($data);
        return $obj;
    }


}


?>