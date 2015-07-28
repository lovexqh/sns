<?php
class ClubNoticeModel extends Model {
	
	public function getNoticeList($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$noticeList = $this->where( $map )->order( 'ctime desc' )->limit( 8 )->select();
		return $noticeList;
	}
	
	public function saveNotice(){
		$map['clubid'] = $_POST['clubid'];//clubid
		$map['uid'] = $GLOBALS["_SESSION"]['mid'];//uid
		$map['content'] = trim($_POST['content']);//公告内容
		$map['ctime'] = time();
		$map['isdel'] = 0;
		$rs = $this->add($map);
		return $rs;
	}
	
	//isdel= 1
	public function delNotice(){
		$id = $_POST['id'];
		$map['isdel'] = 1;
		$rs = $this->where('id='.$id)->save($map);
		return $rs;
	}

	/**
	 * @title  获取最新公告,1条
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2014-1-26
	 */
	public function getLatestNotice($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$noticeInfo = $this->where($map)->order('ctime desc')->find();
		return $noticeInfo;
	}
	
	public function getAllNotice($clubid){
		$noticeList = $this->where('clubid='.$clubid.' AND isdel=0')->order('ctime desc')->select();
		return $noticeList;
	}
	
	public function addNotice($map){
		$rs = $this->add($map);
		return $rs;
	}
	
	public function getNoticeById($id){
		$noticeinfo = $this->where('id='.$id.' AND isdel=0')->find();
		return $noticeinfo;
	}
	
	public function deleteNotice($map, $id){
		$rs = $this->where('id='.$id)->save( $map );
		return $rs;
	}
	
	/**
	 * @title  获取所有社团公告列表
	 * @description 查询的数量
	 * @param
	 * @return
	 * @author	xiawei 2014-3-17
	 */
	public function getAllNoticeList($num){
		$map['isdel'] = 0;
		$noticeList = $this->where($map)->order('ctime desc')->limit($num)->select();
		return $noticeList;
	}

}