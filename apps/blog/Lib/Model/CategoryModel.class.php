<?php
/**
 +------------------------------------------------------------------------------
 * 广场栏目Model
 +------------------------------------------------------------------------------
 * @category	square
 * @package		Lib/Model
 * @author		美美 <meimeili@gridinfo.com.cn>
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-3-4 上午02:31:37
 +------------------------------------------------------------------------------
 */
class CategoryModel extends Model {
	protected $tableName = 'blog_category';
	/**
	 * 通过Cid得到用户的id
	 * @param $cid
	 */
	public function getUserByCid($cid){
		$map['id'] = $cid;
		$category = $this->where($map)->find();
		return $category;
	}

	/**
	 * 得到用户的分类信息
	 * @param $uid
	 * @return mixed
	 */
	public function getCategory($uid){
		$map['uid'] = $uid;
		$category = $this->where($map)->select();
		return $category;
	}

	public function addCategory($data){
		$cateid = $this->add($data);
		$category = $this->getUserByCid($cateid);
		return $category;
	}

	public function upCategory($map=null,$data){
		return $this->where($map)->save($data);

	}

	public function delCategroy($map=null){
		$category = $this->where($map)->delete();
		$map['category'] = $map['id'];
		unset($map['id']);
		$blog = D('blog')->where($map)->delete();

		if($category  === false && $blog  === false){
			return false;
		}else{
			return true;
		}

	}

	public function getCategoryInfo($map =null){
		$category = $this->where($map)->find();
		return $category;
	}
}
?>