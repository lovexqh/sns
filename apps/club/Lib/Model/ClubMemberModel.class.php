<?php
class ClubMemberModel extends Model {
	
	/**
	 *
	 * @title 根据uid获取用户所在的社团
	 * @description
	 * @param $uid
	 * @return
	 * @author	xiawei 2013-11-13
	 */
	public function getClubByUid($uid){
		
		$myClubEvent = M('')->table ( C ( 'DB_PREFIX' ) . 'club c,' . C ( 'DB_PREFIX' ) . 'club_member m' )
							->where ( 'c.id=m.clubid AND c.isdel=0 AND m.type IN (1,2,3) AND m.uid='.$uid )
							->field ( 'c.*' )
							->order ('c.type asc, c.ctime desc')
							->select();
		//时间转换
		foreach($myClubEvent as &$myClub){
			$myClub['ctime'] = date("Y-m-d",$myClub['ctime']);
		}
		return $myClubEvent;
	}
	
	/**
	 * @title  获取申请加入社团的成员
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-11-25
	 */
	public function getApplyMember($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = 0;
		$applyMember = $this->where( $map )->order('ctime asc')->select();
		return $applyMember;
	}
	
	/**
	 * @title  查找某人在某社团中的信息
	 * @description 
	 * @param  $clubid, $uid
	 * @return
	 * @author	xiawei 2013-11-25
	 */
	public function getMemberInfoInClub($clubid, $uid){
		$map['clubid'] = $clubid;
		$map['uid'] = $uid;
		$memberInfo = $this->where( $map )->find();
		return $memberInfo;
	}
	
	/**
	 * @title  根据社团id查找该社团的成员
	 * @description 
	 * @param  $clubid
	 * @return  社团成员列表
	 * @author	xiawei 2013-12-5
	 */
	public function getMemberByClubid($map){
		$map['type'] = array('in','1,2,3');
		$memberList = $this->where( $map )->order('type asc, mtime asc')->findPage( 20 );
		return $memberList;
	}
	
	/**
	 * @title  根据部门id查找该部门下的成员
	 * @description 
	 * @param  部门id
	 * @return  成员list
	 * @author	xiawei 2013-12-6
	 */
	public function getMemberByDeptid( $id ){
		$map['deptid'] = $id;
		$map['type'] = array('in', '1,2,3');
		$memberList = $this->where( $map )->select();
		return $memberList;
	}
	
	/**
	 * @title  若删除部门,将部门下的成员的部门id设为0
	 * @description 
	 * @param  部门id
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function setMemberDeptid($id){
		$rs = $this->where( 'deptid='.$id )->save( array('deptid'=>0) );
		return $rs;
	}
	
	/**
	 * @title  更改成员type
	 * @description  升为管理员,取消管理员,换届,踢出
	 * @param  成员id
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function chgMemberType($id, $map){
		$rs = $this->where('id='.$id)->save($map);
		return $rs;
	}
	
	/**
	 * @title  查找待审核的成员
	 * @description 
	 * @param  社团id
	 * @return
	 * @author	xiawei 2013-12-11
	 */
	public function getAuditMember($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = 0;
		$auditMember = $this->where( $map )->order('ctime desc')->findPage(30);
		return $auditMember;
	}
	
	/**
	 * @title  对待审核的成员进行拒绝操作
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-11
	 */
	public function auditRejectMember($id, $mtime){
		$map['type'] = 6;
		$map['mtime'] = $mtime;
		$rs = $this->where( 'id='.$id )->save( $map );
		return $rs;
	}
	
	/**
	 * @title  查找已退出的成员
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-11
	 */
	public function getCancelMember($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = 4;
		$cancelMember = $this->where( $map )->order('mtime desc')->findPage( 30 );
		return $cancelMember;
	}
	
	/**
	 * @title  修改社团成员所在部门
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-24
	 */
	public function chgMemberDept($data, $memid){
		$rs = $this->where('id='.$memid)->save( $data );
		return $rs;
	}
	
	public function addMember($map){
		$rs = $this->add($map);
		return $rs;
	}

	public function getMemberById($id){
		$meminfo = $this->where('id='.$id)->find();
		return $meminfo;
	}

	public function getMemberCount($clubid){
		$map['clubid'] = $clubid;
		$map['type'] = array('in', '1,2,3');
		$count = $this->where( $map )->count();
		return $count;
	}
	
	public function getMemberInfoByClubid( $clubid ){
		$map['clubid'] = $clubid;
		$map['type'] = array('in', '1,2,3');
		$memberUid = $this->where( $map )->select();
		return $memberUid;
	}
	
	public function updateMember($id, $map){
		$rs = $this->where( 'id='.$id )->save( $map );
		return $rs;
	}
	
	/**
	 * @title  获取社团中的管理员
	 * @description 
	 * @param  社团id
	 * @return
	 * @author	xiawei 2014-1-17
	 */
	public function getManagerByClubid( $clubid ){
		$map['clubid'] = $clubid;
		$map['type'] = array('in', '1,2');
		$managerList = $this->where( $map )->select();
		return $managerList;
	}
	
	public function getUcUid($uid){
		$ucUid = M('ucenter_user_link')->where('uid = '.$uid)->getField('uc_uid');
		return $ucUid;
	}
	
	public function getMemberByParam($map){
		$memList = $this->where($map)->order('type asc, mtime asc')->select();
		return $memList;
	}
}