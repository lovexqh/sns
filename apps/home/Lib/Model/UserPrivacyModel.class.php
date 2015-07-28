<?php
/**
 +------------------------------------------------------------------------------
 * 用户权限操作相关Model
 +------------------------------------------------------------------------------
 * @category	home
 * @package		Lib/Model
 * @author		小波 <xiaobosun@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-5 15:00:53
 +------------------------------------------------------------------------------
 */
class UserPrivacyModel extends Model {
	protected	$tableName	=	'user_privacy';

	/**
	 +----------------------------------------------------------
	 * 获取用户设置
	 +----------------------------------------------------------
	 * @param	int		$mid	用户ID
	 * @return	array	用户相关的设置集合
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:01:46
	 +----------------------------------------------------------
	 */
	function getUserSet($mid) 	{
		$userPrivacy = $this->where("uid=$mid")->field('`key`,`value`')->findall();
		if($userPrivacy){
			foreach ($userPrivacy as $k=>$v){
				$r[$v['key']] = $v['value'];
			}
			return $r;
		}else{
			return $this->defaultSet();
		}
	}

	/**
	 +----------------------------------------------------------
	 * 保存用户设置
	 +----------------------------------------------------------
	 * @param	array		$data	用户要设置的信息信息集合
	 * @param	int			$uid	用户ID
	 * @return	int 		返回保存后的成功或失败代码
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:02:46
	 +----------------------------------------------------------
	 */
	function dosave($data,$uid){
		if(empty($uid)){
			return false;
		}
		$map = array();
		$map['uid'] = $uid;
		$this->where($map)->delete();
		foreach ($data as $key=>$value){
			$sql[] = "($uid,'{$key}',{$value})";
		}
		$result = $this->execute("INSERT INTO {$this->tablePrefix}user_privacy (uid,`key`,`value`) VALUES ".implode(',', $sql));
		return $result;
	}

	/**
	 +----------------------------------------------------------
	 * 默认配置
	 +----------------------------------------------------------
	 * @return	array		默认的配置集合
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:05:01
	 +----------------------------------------------------------
	 */
	private function defaultSet(){
		return array(
			'weibo_comment' => 0, //(所有人，除黑名单)
			'message' => 0,	//(所有人，除黑名单)
		);
	}

	/**
	 +----------------------------------------------------------
	 * 获取权限
	 +----------------------------------------------------------
	 * @param	int		$mid	当前登录用户的ID
	 * @param	int		$uid	要查看用户的ID
	 * @return	array	返回查询后的权限集合
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:05:52
	 +----------------------------------------------------------
	 */
	function getPrivacy($mid,$uid){
		if($mid==$uid) {
			$data['weibo_comment'] = true;
			$data['message']       = true;
			$data['follow']        = true;
			return $data;
		}

		$isBackList  = isBlackList($uid, $mid);
		$followState = getFollowState($uid, $mid) != 'unfollow';
		$userset     = $this->getUserSet($uid);
		if ($isBackList) {
			$data['weibo_comment'] = false;
			$data['message']       = false;
			$data['follow']        = false;
			$data['blacklist']     = true;
		}else {
			$data['weibo_comment'] = ( $userset['weibo_comment'] )? $followState : true;
			$data['message']       = ( $userset['message'] )? $followState : true;
			$data['follow']        = true;
			$data['blacklist']     = false;
		}

		return $data;
	}

	/**
	 +----------------------------------------------------------
	 * 设置黑名单
	 +----------------------------------------------------------
	 * @param	int		$mid	当前登录用户的ID
	 * @param	string	$type	操作类型（add:添加 del:删除）
	 * @return	bool 	返回操作后的成功或失败值
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:07:50
	 +----------------------------------------------------------
	 */
	function setBlackList($mid,$type,$fid){
		if($type=='add'){
			$map['uid'] = $mid;
			$map['fid'] = $fid;
			if( M('user_blacklist')->where($map)->count()==0 ){
				$map['ctime'] = time();
				M('user_blacklist')->add($map);  //添加黑名单
				M('weibo_follow')->where("(uid=$mid AND fid=$fid) OR (uid=$fid AND fid=$mid)")->delete(); //自动解除关系
				return true;
			}else{
				return false;
			}
		}else{
			$map['uid'] = $mid;
			$map['fid'] = $fid;
			return M('user_blacklist')->where($map)->delete();
		}
	}

	/**
	 +----------------------------------------------------------
	 * 获取黑名单列表
	 +----------------------------------------------------------
	 * @param	int		$mid	当前登录用户的ID
	 * @return	array	返回查询后的结果集
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:09:54
	 +----------------------------------------------------------
	 */
	function getBlackList($mid){
		return M('user_blacklist')->where("uid=$mid")->findall();
	}

	/**
	 +----------------------------------------------------------
	 * 判断用户是否是黑名单关系
	 +----------------------------------------------------------
	 * @param	int		$uid	要查看用户的ID
	 * @param	int		$mid	当前登录用户的ID
	 * @return	array	返回查询的结果集
	 * @author	小波
	 +----------------------------------------------------------
	 * 创建时间：2013-3-5 15:17:00
	 +----------------------------------------------------------
	 */
	function isInBlackList($uid,$mid){
		$uid = intval($uid);
		$mid = intval($mid);
		$result = M('user_blacklist')->where("uid=$mid AND fid=$uid")->find();
		return	$result;
	}
}
?>