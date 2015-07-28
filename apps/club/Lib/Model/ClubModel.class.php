<?php
class ClubModel extends Model {
	function  _initialize()
	{
		parent::_initialize();
	}

	public function getClubById($clubid){
		$clubInfo = $this->where('id='.$clubid.' AND isdel=0')->find();
		return $clubInfo;
	}
	
	/**
	 * 
	 * @title  根据uid获取所在的社团
	 * @description 
	 * @param  $uid
	 * @return  所在的社团列表
	 * @author	xiawei 2013-11-23
	 */
	public function getClubByUid($uid){
		$myClub = $this->where('uid=' .$uid. ' AND isdel=0')->order('type asc')->select();
		return $myClub;
	}
	
	/**
	 * @title  获取所有社团
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-23
	 */
	public function getAllClub($type, $condition='0,1000', $mid=null){
		if(empty($type)){
			//$allClub = $this->where('isdel=0')->limit($condition)->select();
			$cache = $this->field(' distinct club.*, member.uid as mid ')
						  ->table(C('DB_PREFIX').'club as club left join '.C('DB_PREFIX').'club_member as member on club.id = member.clubid and member.uid='.$mid.' ')
			              ->where(' club.isdel=0 ')
			              ->limit($condition)->select();
		}else{
			//$allClub = $this->where('isdel=0 AND type='.$type)->limit($condition)->select();
			$cache = $this->field(' distinct club.*, member.uid as mid ')
					      ->table(C('DB_PREFIX').'club as club left join '.C('DB_PREFIX').'club_member as member on club.id = member.clubid and member.uid='.$_SESSION['ucInfo'][uid].' ')
						  ->where(' club.isdel=0 and club.type='.$type.' ')
						  ->limit($condition)->select();
		}
		//转换时间
		foreach ($cache as &$value){
			$value['ctime'] = date("Y-m-d", $value['ctime']);
		}
		//获取总社团数
		$count = $this->where(' isdel = 0 ')->count();
		$cache['count'] = $count;
		return $cache;
	}
	
	/**
	 * 获取社团类型
	 * @param unknown $clubid
	 * @return unknown
	 */
	public function getClubType(){
        return array(
            array('name'=>'学校社团','id'=>1),
            array('name'=>'学院社团','id'=>2),
            array('name'=>'学生社团','id'=>3),
        );
	}
	
	public function getClubBalanceById($clubid){
		$map['id'] = $clubid;
		$map['isdel'] = 0;
		$balance = $this->where($map)->field('balance')->find();
		return $balance;
	}
	
	/**
	 * @title  根据社团名称查询社团
	 * @description
	 * @param
	 * @return
	 * @author	lihao 2013-12-18
	 */
	public function getClubByTitle($title, $type, $condition='0,1000',$mid=null){
		if(empty($type)){
			//$allClub = $this->where('isdel = 0 AND title like \'%'.$title.'%\'')->select();
			$cache = $this->field(' club.*, member.uid as mid ')
						  ->table(C('DB_PREFIX').'club as club left join '.C('DB_PREFIX').'club_member as member on club.id = member.clubid and member.uid='.$mid.' ')
						  ->where(' club.isdel=0 AND title like \'%'.$title.'%\' ')
						  ->limit($condition)->select();
		}else{
			//$allClub = $this->where('isdel = 0 AND type = '.$type.' AND title like \'%'.$title.'%\'')->select();
			$cache = $this->field(' club.*, member.uid as mid ')
						  ->table(C('DB_PREFIX').'club as club left join '.C('DB_PREFIX').'club_member as member on club.id = member.clubid and member.uid='.$mid.' ')
						  ->where(' club.isdel=0 and club.type='.$type.' AND title like \'%'.$title.'%\' ')
						  ->limit($condition)->select();
		}
		//转换时间
		foreach ($cache as &$value){
			$value['ctime'] = date("Y-m-d", $value['ctime']);
		}
		return $cache;
	}

	/**
	 * @title  删除款项时,更新余额
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function updateDelBalance($clubid, $money, $type){
		if($type == 1){
			$map['balance'] = array( 'exp', 'balance-'.$money );
		}else if($type == 2){
			$map['balance'] = array( 'exp', 'balance+'.$money );
		}
		$rs = $this->where('id='.$clubid)->save($map);
		return $rs;
	}
	
	/**
	 * @title  添加款项时,更新余额
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function updateAddBalance($clubid, $money, $type){
		if($type == 1){
			$map['balance'] = array( 'exp', 'balance+'.$money );
		}else if($type == 2){
			$map['balance'] = array( 'exp', 'balance-'.$money );
		}
		$rs = $this->where('id='.$clubid)->save($map);
		return $rs;
	}
	
	public function getClubByClubname($title, $clubid=''){
		$str = "";
		if($clubid){
			$str = " AND id !=".$clubid;
		}
		$findClub = $this->where( "title='".$title."' AND isdel=0".$str )->find();
		return $findClub;
	}
	
	

	/**
	 * @title  更新社团内容
	 * @description
	 * @param
	 * @return
	 * @author	lihao
	 */
	public function updateClub($id, $map){
		$rs = $this->where('id='.$id)->save($map);
		return $rs;
	}
	
	/**
	 * getClubList
	 *getClubList(1, $condition, null, $order, $limit, 0);
	 */
	public function getClubList($html = 1, $map = array(), $fields= null , $order = null, $limit=null, $isDel=0) {
		//处理where条件
		if(!$isDel)$map[] = 'isdel=0';
		else $map[] = 'isdel=1';
		$map = implode(' AND ', $map);
	
		$function_find = $html ? 'findPage' : 'findAll';
		//连贯查询.获得数据集
		$result = $this->where( $map )->field( $fields )->order( $order )->$function_find($limit) ;
		return $result;
	
	}
	
	/**
	 * 删除社团，以及社团对应的帖子，恢复，文件
	 * @author lihao
	 */
	public function remove($id)
	{
		$id = is_array($id) ? '('.implode(',',$id).')' : '('.$id.')';  //判读是不是数组回收
	
		//$uids = D('Club', 'club')->field('uid')->where('id IN ' . $id)->findAll(); // 创建者ID
		$res  = D('Club', 'club')->setField('isdel', 1, 'id IN ' . $id);  // 回收社团
		if ($res) {
			// 删除成员
			D('ClubMember', 'club')->where('clubid IN ' . $id)->delete();       //删除成员
			// 回收帖子和文件
			D('ClubTopic', 'club')->setField('isdel',1,'clubid IN'.$id); //回收话题
			//获取topicid列表
			$topicids = D('ClubTopic', 'club')->field('id')->where('clubid IN '.$id);
			
			D('ClubReply', 'club')->setField('isdel',1,'topicid IN'.$topicids);  //回收话题回复
			D('ClubDocument', 'club')->setField('isdel',1,'clubid IN'.$id);   //文件回收
			
		   }
		return $res;
	}
	
	/**
	 * @title  修改社团
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-31
	 */
	public function editClub($map, $clubid){
		$rs = $this->where( 'id='.$clubid )->save( $map );
		return $rs;
	}
	
	/**
	 * @title  根据相应条件查询社团
	 * @description 
	 * @param  社团类型,关键字
	 * @return
	 * @author	xiawei 2014-1-26
	 */
	public function getClubByParam($type='', $key=''){
		if($type != ''){			
			$map['type'] = $type;
		}
		if($key != ''){			
			$map['title'] = array('like','%'.$key.'%');
		}
		$map['isdel'] = 0;
		$clubList = $this->where($map)->field('id as cid,logo as chead,title as cname,membercount as cmember,description as cinfo')->order('type asc,ctime asc')->select();
		return $clubList;
	}
}