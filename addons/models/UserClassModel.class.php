<?php
class UserClassModel extends Model {
	protected $tableName = 'user_class';

	/**
	 * 添加用户班级
	 * @param string $data 用户班级内容
	 * @return boolean
	 */
	public function addUserClass($data) {
		$data['ctime']	= time();
		return $this->add($data);
	}

	/**
	 * 删除用户班级
	 * @param string $ids 用户班级ID
	 * @return boolean
	 */
	public function deleteUserClass($ids) {
		//防误操作
		if (empty($ids)) return false;

		$map['class_id']	= array('in', $ids);
		$map1['user_class_id']	= array('in', $ids);
		M('user_class')		->where($map)->delete();
		M('user_class_link')->where($map1)->delete();
		return true;
	}

	/** 检测用户班级是否存在
     * @param unknown_type $title 用户班级名称
     * @param unknown_type $gid   用户班级ID 该函数里为非该用户班级ID
     * @return boolean
     */
	public function isUserClassExist($title) {
		$map['class_name']			= $title;
    	return M('user_class')->where($map)->find();
    }

    /**
     * 指定用户班级下是否存在用户
     * @param array $gids 用户班级ID
     * @return boolean
     */
    public function isUserClassEmpty($cids) {
    	$map['user_class_id']	= array('in', $cids);
    	return ! M('user_class_link')->where($map)->find();
    }
}