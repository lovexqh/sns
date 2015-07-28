<?php
class ClubAccountModel extends Model {
	
	/**
	 * @title  根据社团id查询该社团的财务名目
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function getAccountList($map){
		$map['isdel'] = 0;
		$accountList = $this->where($map)->order('addtime desc,id desc')->findPage(20);
		return $accountList;
	}
	
	/**
	 * @title  查询余额,根据社团id查询该社团最后一条财务名目
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-6
	 */
	public function getLastAccount($clubid){
		$map['clubid'] = $clubid;
		$map['isdel'] = 0;
		$balance = $this->where($map)->order('addtime desc')->field('balance')->find();
		return $balance;
	}
	
	/**
	 * @title  添加财务名目
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-9
	 */
	public function addAccount( $map ){
		$addRs = $this->add( $map );
		return $addRs;
	}
	
	/**
	 * @title  删除财务名目
	 * @description  将表中isdel更新为1,同时将删除人的uid计入updateuid
	 * @param
	 * @return
	 * @author	xiawei 2013-12-9
	 */
	public function delAccount($id, $uid){
		$map['updateuid'] = $uid;
		$map['isdel'] = 1;
		$rs = $this->where( 'id='.$id )->save( $map );
		return $rs;
	}
	
	/**
	 * @title  根据名目id获取名目信息
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-9
	 */
	public function getAccountById($id){
		$accountInfo = $this->where( 'id='.$id )->find();
		return $accountInfo;
	}
	
	/**
	 * @title  删除款项时更新总金额
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function updateDelMoney($id, $money, $uid){
		$map['updateuid'] = $uid;
		$map['totalmoney'] = array( 'exp', 'totalmoney-'.$money );
		$updateRs = $this->where('id='.$id)->save($map);
		return $updateRs;
	}
	
	/**
	 * @title  添加款项时更新总金额
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function updateAddMoney($id, $money, $uid){
		$map['adduid'] = $uid;
		$map['totalmoney'] = array( 'exp', 'totalmoney+'.$money );
		$updateRs = $this->where('id='.$id)->save($map);
		return $updateRs;
	}
	
	public function getAccountForMobile($map,$start,$num){
		$map['isdel'] = 0;
		$accountList = $this->where($map)->order('addtime desc')->field('id,clubid,title,type,totalmoney,addtime,adduid')->limit($start.','.$num)->select();
		return $accountList;
	}

}