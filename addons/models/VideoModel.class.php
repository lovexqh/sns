<?php
/**
 * 视频模型
 *
 * @author melec
 */
class VideoModel extends Model {
	protected $tableName = 'video_new';

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
		return $this->where("id = $id")->delete();
	}
	/**
	 * 
	 * 更新记录
	 */
	public function updateItem($map){
		return $this->save($map);
	}
}