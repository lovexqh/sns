<?php
class UserApi extends Api{

    function appAuth(){
        $uid = (int)$_REQUEST['uid']?(int)$_REQUEST['uid']:exit;
        $auth = get_userAppLimit_by_uid($uid);
        $return = array();
        if(!empty($auth)){
            $apps = M('app')->field('app_name,app_alias')->findAll();
            $app=array();
            foreach($apps as $v){
                $app[$v['app_name']] = $v['app_alias'];
            }
            unset($apps);
            foreach($auth as $k=>$v){
                $alias = $app[$v['app_name']];
                if($alias){
                    $auth[$k]['app_alias'] = $alias;
                }else{
                    unset($auth[$k]);
                }
            }
            $auth = array_values($auth);
            $return['status'] = 1;
            $return['data'] = $auth;
            $return['statusCode'] = '连接成功';
        }else{
            $return['status'] = 0;
            $return['data'] = array();
            $return['statusCode'] = '失败';
        }
        return $return;
    }

    function allApp(){
        $apps = M('app')->field('app_name,app_alias')->findAll();
        if(!empty($apps)){
            return array('status'=>1,'data'=>$apps,'statusCode'=>'连接成功');
        }else{
            return array('status'=>0,'data'=>array(),'statusCode'=>'失败');
        }
    }


	//按用户UID或昵称返回用户资料，同时也将返回用户的最新发布的微广播
	function show(){
		$data = getUserInfo($this->user_id, urldecode($this->user_name),$this->mid,true);
		return $data;
	}

	/**
     * 获取给定用户的通知统计
     * @param int $mid
     * @return array 格式为:
     *               <code>
     *               array(
     *               	'message'		=> '0', // 未读短消息数
     *               	'notify'		=> '0', // 未读通知数
     *               	'appmessage'	=> '0', // 未读应用消息数
     *               	'comment'		=> '0', // 未读评论总数
     *               	'atme'			=> '0', // 未读的@我的总数
     *               	'total'			=> '0', // 以上未读的总数
     *               	'weibo_comment' => '0', // 未读的微广播评论数
     *               	'global_comment'=> '0', // 未读的其它应用评论数
     *                  'group_atme'     => '0', // 社团@我
     *                  'group_comment'     => '0', // 社团评论
     *                  'group_bbs'     => '0', // 社团帖子通知
     *               )
     *               </code>
     */
	public function notificationCount() {
		if(empty($this->user_id) && isset($this->mid)){
			return service('Notify')->getCount($this->mid);
		}else{
			return service('Notify')->getCount($this->user_id);
		}

	}
	
	public function unsetNotificationCount()
	{
		if(empty($this->user_id) && isset($this->mid)){
			switch ($this->data['type']) { // 暂仅允许message/weibo_commnet/atMe
				case 'message':
					return (int) model('Message')->setAllIsRead($this->mid);
				case 'weibo_comment':
					return (int) model('UserCount')->setZero($this->mid, 'comment');
				case 'atMe':
					return (int) model('UserCount')->setZero($this->mid, 'atme');
				default:
					return 0;
			}
		}else{
			switch ($this->data['type']) { // 暂仅允许message/weibo_commnet/atMe
				case 'message':
					return (int) model('Message')->setAllIsRead($this->user_id);
				case 'weibo_comment':
					return (int) model('UserCount')->setZero($this->user_id, 'comment');
				case 'atMe':
					return (int) model('UserCount')->setZero($this->user_id, 'atme');
				default:
					return 0;
			}
		}
	}
	
	/**
	 * 获取给定用户的通知统计
	 * @param int $mid
	 * @return array 格式为:
	 *               <code>
	 *               array(
	 *               	'message'		=> '0', // 未读短消息数
	 *               	'notify'		=> '0', // 未读通知数
	 *               	'comment'		=> '0', // 未读评论总数
	 *               	'atme'			=> '0', // 未读的@我的总数
	 *               	'total'			=> '0', // 以上未读的总数
	 *               )
	 *               </code>
	 */
	public function getNotificationList(){
		$this->data['type'] 	= $this->data['type']	? $this->data['type'] : array(1,2);
		$this->data['order']	= $this->data['order'] == 'ASC'	? '`mb`.`list_id` ASC' : '`mb`.`list_id` DESC';
		if(empty($this->user_id) && isset($this->mid)){
			return service('Notify')->getNotifityCount($this->mid, $this->data['type'], $this->since_id, $this->max_id, $this->count, $this->page);
		}else{
			return service('Notify')->getNotifityCount($this->user_id, $this->data['type'], $this->since_id, $this->max_id, $this->count, $this->page);
		}		
	}
	
	/**
     * 设置私信为已读
     *
     * @param array|string $message_ids 多个私信ID可以组成数组，也可以用“,”分隔
     * @param int          $uid 成员的用户ID
     * @return boolean
     */
	public function setMessageIsRead(){
		if(empty($this->user_id) && isset($this->mid)){
			return (int)model('Message')->setMessageIsRead($this->id,$this->mid);
		}else{
			return (int)model('Message')->setMessageIsRead($this->id,$this->user_id);
		}
		
	}
}