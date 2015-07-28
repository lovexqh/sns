<?php
class PhotoModel extends Model {
	var $tableName	=	'photo';


	public function getListPhoto($privicy = 1,$order=' cTime DESC ',$page = 20){
		$map['_string'] = ' pa.privacy = '.$privicy.' AND p.isDel = 0 And pa.isDel = 0 AND p.albumId = pa.id ';
		$photo = $this->field('p.*,pa.name as catename,pa.id as cateid')->table(C('DB_PREFIX').'photo as p,'.C('DB_PREFIX').'photo_album pa')->where($map)->order($order)->findpage($page);
		return $photo;
	}

	public function getList($order = 'cTime DESC',$page=10){
		$map['privacy'] = 1;
		$photo = $this->field('savepath,name,id')->where($map)->order($order)->limit($page)->select();
		return $photo;
	}
	/**
	 * 得到用户排行榜
	 * @param int $num
	 * @return mixed
	 */
	public function getHotUser($num = 6){
		$hotuser = $this->field('count(id) as num,userId')->group('userId')->limit($num)->order(' num DESC ')->select();
		return $hotuser;
	}

	/**
	 * 得到相册照片基本信息
	 */
	public function getPhotoInfo($id){
		if(empty($id)){
			return false;
		}
		$photo = M('photo')->where('id = '.$id)->find();
		return $photo;

	}

	public function getSearchPhoto($keyword,$pagesize = 20){
		//$map['_string'] = " p.albumId = pa.id AND p.name LIKE '%$keyword%' OR pa.name LIKE '%$keyword%' ";
		$map['_string'] = " name LIKE '%$keyword%' AND isDel = 0 AND (privacy = 1 or privacy = 4 )";
		//$photlist = M()->field('p.*,pa.id as cateid ,pa.name as catename')->table(C('DB_PREFIX').'photo p,'.C('DB_PREFIX').'photo_album pa')->where($map)->findpage($pagesize);
		$photlist = $this->where($map)->order('mTime desc')->findpage($pagesize);
		return $photlist;
	}

	/**
	 * 根据条件返回对应条件的ID，嘿嘿
	 * @param $map
	 * @param string $order
	 * @return mixed
	 */
	public function getProNext($map,$order= 'id DESC'){
		$id = M('photo')->where($map)->order($order)->getField('id');
		if(is_null($id)){
			return '';
		}
		return $id;
	}

	/**
	 * 得到用户所有上传照片的数量
	 * @param $uid
	 * @return mixed
	 */
	public function getPhotoNum($uid){
		return M('photo')->where('userId = '.$uid)->count();
	}



	public function upPhotoNum($id){
		return M('photo')->where('id = '.$id)->setInc('readCount');
	}

	public function getPhotoNumList($num = 20,$order = 'cTime DESC'){
		$photo	=	M('photo')->where('privacy = 1')->order($order)->limit($num)->select();
		//echo M()->getLastSql();
		return $photo;
	}

	public function getUidFeature($uid) {
		$map ['userId'] = $uid;
		$map ['feature'] = 1;
		$result = $this->where ( $map )->order ( "sorder ASC" )->findAll ();
		return $result;
	}
	public function setCount($appid, $count) {
		$map ['id'] = $appid;
		$map2 ['commentCount'] = $count;
		return $this->where ( $map )->save ( $map2 );
	}

	public function setFeature($pid,$uid){
		$condition['id'] = $pid;
		$condition['userId'] = $uid;
		$featureCount = $this->getFeatureCount($uid);
		if($featureCount == 3){
			$old_feature = $this->getUidFeature($uid);
			foreach($old_feature as $value){
				//取消最下面的特色展示
				if($value['sorder'] == 3){
					$rm['sorder'] = 0;
					$rm['feature'] = 0;
					$this->where('id='.$value['id'])->save($rm);
					continue;
				}
				$new_order = array();
				$new_order['sorder'] = $value['sorder'] + 1;
				$this->where('id='.$value['id'])->save($new_order);
			}
			//设置最新的这个图为第一个特色展示
			$map['sorder'] = 1;
		}else{
			$map['sorder'] = $featureCount+1;
		}
		$map['feature'] = 1;
		//设置为特色.并设置排序
		return $this->where($condition)->save($map)?1:0;
	}

	public function setDownOrder($pid,$uid){
		$featureCount = $this->getFeatureCount($uid);
		if($featureCount == 1) return -2; //只有一个特色的情况下，不需要进行调换
		$old_order = $this->where('id='.$pid)->getField('sorder');
		if($featureCount ==3 && $old_order == 3) return -1;//最后一个无法向下调换
		$feature   = $this->getUidFeature($uid);
		$now_feature = $this->getOrderFeature($feature);

		$map_current['sorder'] = $old_order+1;
		$map_current_condition['id']     = $pid;
		//当前的排序增加一个
		$this->where($map_current_condition)->save($map_current);

		//下一个照片的排序换成当前的
		$map_next['sorder'] = $old_order;
		$map_next_condition['id'] = $this->getNextFeature($now_feature,$old_order,true);
		$this->where($map_next_condition)->save($map_next);
		return 1;
	}

	private function getNextFeature($featureOrder,$order,$add = true){
		foreach($featureOrder as $key=>$value){
			if($add){
				if($value == $order+1){
					return $key;
				}
			}else{
				if($value == $order-1){
					return $key;
				}
			}

		}
	}

	private function getOrderFeature($feature){
		$result = array();
		foreach($feature as $value){
			$result[$value['id']] = $value['sorder'];
		}
		return $result;
	}
	public function setUpOrder($pid,$uid){
		$featureCount = $this->getFeatureCount($uid);
		if($featureCount == 1) return -2; //只有一个特色的情况下，不需要进行调换
		$old_order = $this->where('id='.$pid)->getField('sorder');
		if($featureCount ==1 && $old_order == 3) return -1;//第一个无法向上调换
		$feature   = $this->getUidFeature($uid);
		$now_feature = $this->getOrderFeature($feature);

		//当前的排序减少一个
		$map_current['sorder']           = $old_order-1;
		$map_current_condition['id']     = $pid;
		$this->where($map_current_condition)->save($map_current);

		//上一个照片的排序换成当前的
		$map_next['sorder'] = $old_order;
		$map_next_condition['id'] = $this->getNextFeature($now_feature,$old_order,false);
		$this->where($map_next_condition)->save($map_next);
		return 1;
	}
	public function getFeatureCount($uid){
		$map['userId'] = $uid;
		$map['feature'] = 1;
		$count = $this->field('count(1) as count')->where($map)->find();
		return $count['count'];
	}

	public function getPhotoAllList($pagesize='20'){
		$photos	=	M()->table(C('DB_PREFIX').'photo a,'.C('DB_PREFIX').'photo_album b')->field('a.*,b.name as albumname')->where('a.albumId = b.id and b.privacy = 1')->order('a.cTime DESC')->findPage($pagesize);
		//echo M()->getLastSql();
		return $photos;
	}
	public function getPhotoById($id){
		$photo	=	M()->table(C('DB_PREFIX').'photo a,'.C('DB_PREFIX').'photo_album b')->field('a.*,b.name as albumname')->where('a.albumId = b.id and b.privacy = 1 and a.id = '.$id)->order('a.cTime DESC')->find();
		//echo M()->getLastSql();
		return $photo;
	}

	/**
	 *
	+----------------------------------------------------------
	 * 社交圈相册列表
	+----------------------------------------------------------
	 * @author Kung 2013-12-13
	+----------------------------------------------------------
	 */

	public function getPhotoList($memberList,$limit=2){
		$map['userId'] = array('IN',$memberList);
		$map['privacy'] = '1';

		$result = $this->where($map)->order("cTime desc")->findPage($limit);
		return $result;
	}

	/**
	 *
	+----------------------------------------------------------
	 * 为圈子应用写的，利用photo_link获取本圈子人的photo列表
	+----------------------------------------------------------
	 * @param array $bjid,$zyid,$yxid,$nj      班级，专业，院系，年级
	 * @return array
	 * @author Kung 2013-12-19
	+----------------------------------------------------------
	 */
	function getPhotoForSociety($param,$limit = 10){
		if($param['uidstr'] != null)
			$map['bl.uid'] = array('in',$param['uidstr']);
		if($param['bjid'] != null)
			$map['bl.bjid'] = $param['bjid'];
		if($param['zyid'] != null)
			$map['bl.zyid'] = $param['zyid'];
		if($param['nj'] != null)
			$map['bl.nj']   = $param['nj'];
		if($param['yxid'] != null)
			$map['bl.yxid'] = $param['yxid'];
		if($param['depid'] != null)
			$map['bl.depid'] = $param['depid'];
		$map['b.privacy']   = 1;
		$photo_result = M()->table("".C('DB_PREFIX')."photo_link as bl left join ".C('DB_PREFIX')."photo as b on bl.photoid=b.id")
			->field("b.id,b.attachId,b.albumId,b.userId,b.status,b.name,b.cTime,b.info,b.commentCount,b.readCount,b.savepath,b.size,b.tags,b.order")
			->where($map)
			->order("b.cTime desc")
			->findPage($limit);
		return $photo_result;
	}
	/**
	 *
	 +----------------------------------------------------------
	 * 为圈子应用写的，利用photo_link获取本圈子人的photo列表
	 +----------------------------------------------------------
	 * @param array $bjid,$zyid,$yxid,$nj      班级，专业，院系，年级
	 * @return array
	 * @author ssq 2014-2-19
	 +----------------------------------------------------------
	 */
	function getPhotoForSocietyForApi($param,$page,$count = 10){
		$limit = ($page-1)*$count;
		$limit = $limit.",".$count;
		if($param['uidstr'] != null)
			$map['bl.uid'] = array('in',$param['uidstr']);
		if($param['bjid'] != null)
			$map['bl.bjid'] = $param['bjid'];
		if($param['zyid'] != null)
			$map['bl.zyid'] = $param['zyid'];
		if($param['nj'] != null)
			$map['bl.nj']   = $param['nj'];
		if($param['yxid'] != null)
			$map['bl.yxid'] = $param['yxid'];
		if($param['depid'] != null)
			$map['bl.depid'] = $param['depid'];
		$map['b.privacy']   = 1;
		$photo_result = M()->table("".C('DB_PREFIX')."photo_link as bl left join ".C('DB_PREFIX')."photo as b on bl.photoid=b.id")
		->field("b.id,b.attachId,b.albumId,b.userId,b.status,b.name,b.cTime,b.info,b.commentCount,b.readCount,b.savepath,b.size,b.tags,b.order")
		->where($map)
		->order("b.cTime desc")
		->limit($limit)
		->select();
		return $photo_result;
	}

	/**
	 *
	 +----------------------------------------------------------
	 * photo 更改
	 +----------------------------------------------------------
	 * @author ssq 2014-2-13
	 +----------------------------------------------------------
	 */
	
	public function updatePhoto($albumid,$privacy){
		$result = $this->where(array('albumid'=>$albumid))
				->save(array('privacy'=>$privacy)); 
		return $result;
	}
	
	/**
	 * 获取所有相册列表    同步社交圈photo_link表
	 * ----------------------------------
	 * @author ssq     2014-2-12
	 */
	function getPhotoLists(){
		$result = D('photo')
				->field("id,userId")
				->order('userId')
				->select();
		return $result;
	}
}
?>