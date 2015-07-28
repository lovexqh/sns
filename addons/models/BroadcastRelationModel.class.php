<?php
/**
 * 其他应用引用资源表，中间表
 * 
 * @author 朱长奇 <chanqizhuu@gridinfo.com.cn>
 */
class BroadcastRelationModel extends Model {
	protected $tableName = 'broadcast_relation';
	
	/**
	 +----------------------------------------------------------
	 * 其他应用选择资源表信息，然后保存资源表和该应用的关系信息
	 +----------------------------------------------------------
	 * @map数组值		appid 应用id(应用名称，上传1 备课2 课堂3 教研4) appfid对应应用信息id
	 * @author	朱长奇
	 * @param	int keycode 资源类型，1视频  2文本
	 +----------------------------------------------------------
	 * 创建时间：	2013-11-15 上午06:43:20
	 +----------------------------------------------------------
	 */
	  public function saverelation($map,$addvals,$delvals){
		  foreach($addvals as $k=>&$v){
			$map['bid'] = $v;
			$this->add($map);
		  }
		  if($delvals){
				$delmap=$map;
				$delmap['bid']=array('in',$delvals);
				$this->where($delmap)->delete();
		  }
		  return true;
	  }
	/**
	 +----------------------------------------------------------
	 *	其他应用引用资源表，中间表
	 +----------------------------------------------------------
	 * @param	$type 为当前节点的属性
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-10 上午11:07:09
	 +----------------------------------------------------------
	 */
	public function getListByMap($map){
		return	M()->table(C('DB_PREFIX').'broadcast_relation r')->join(C('DB_PREFIX').'broadcast b on r.bid=b.id')->field('b.title,r.*')->where($map)->order("id ASC")->findall();;
	}
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
	/**
	 * 
	 * 查询记录
	 */
	public function getItem($bid){
		$result = $this->where("bid = $bid")->select($map);
		if(empty($result['appid'])){
			return 0;//是数组的话,说明有数据。返回创建失败
		}else{
			return 1;//不是数组，说明或者查询错误,或者查询不到.可以创建
		}

	}
}