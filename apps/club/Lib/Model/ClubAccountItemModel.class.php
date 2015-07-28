<?php
class ClubAccountItemModel extends Model {
	
	/**
	 * @title  根据条目id获取该条目中的款项信息
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-9
	 */
	public function getAccountItem( $id ){
		$map['accountid'] = $id;
		$map['isdel'] = 0;
		$accountItem = $this->where( $map )->order('addtime asc')->select();
		return $accountItem;
	}
	
	/**
	 * @title  删除条目中一条款项记录
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-9
	 */
	public function delAccountItem($itemid, $uid){
		$map['deluid'] = $uid;
		$map['isdel'] = 1;
		$rs = $this->where( 'id='.$itemid )->save( $map );
		return $rs;
	}
	
	public function getAccountItemById( $id ){
		$accountItemInfo = $this->where( 'id='.$id )->find();
		return $accountItemInfo;
	}
	
	/**
	 * @title  添加款项
	 * @description 
	 * @param
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function addAccountItem($map){
		$rs = $this->add($map);
		return $rs;
	}
	
	/**
	 * @title  删除名目时,同时删除名目下的款项
	 * @description 
	 * @param  名目id
	 * @return
	 * @author	xiawei 2013-12-10
	 */
	public function delItemByAccountid($id, $uid){
		$map['deluid'] = $uid;
		$map['isdel'] = 1;
		$rs = $this->where('accountid='.$id)->save($map);
		return $rs;
	}

}