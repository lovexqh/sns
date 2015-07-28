<?php
/**
 *
 */
class BroadcastModel extends Model {
	protected $tableName = 'broadcast';
	/**
	 * 
	 * 创建记录
	 */
	public function createItem($map){
		return $this->add($map);
	}
	/**
	 * 
	 * 删除记录
	 */
	public function delItem($id){
		$del =  $this->where("id = $id")->delete();
		return $del;
	}	
	/**
	 * 
	 * 更新记录
	 */
	public function updateItem($map){
		return $this->save($map);
	}
	public function getItem($id){
		$result = $this->where("id = $id")->select();
		return $result['0'];
	}
}