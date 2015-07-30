<?php
class UndercoverRoomModel extends Model{
	private $api;


	public function _initialize(){
	     	//$this->api = new TS_API();
	}

	public function getRoomList($uid = null,$type = null,$ptype = null,$title = null){
		isset($ptype) && $map['ptype'] = $ptype;
		isset($type) && $map['type'] = $type;
        isset($title) && $map['title'] = array( 'like','%'.$title.'%' );
		if(is_array($uid)&&!empty($uid)){
			$map['uid'] = array('in',$uid);
		}elseif(intval($uid)){
			$map['uid'] = $uid;
		}
	    $result = $this->where($map)->order('cTime DESC')->findPage(20);
	    return $result;
	}
	
	public function getRoom($id,$mid){
		$map['id'] = $id;
		if(isset($mid))$map['uid'] = $mid;
        $result = $this->where($map)->find();
        if(!$result) return false;
        return $result;
	}

	public function deleteRoom($id,$mid){
		$room = $this->where('id='.$id)->find();
		if(!$room) return -2;
		if($room['uid'] != $mid) return -1;

		$rs = $this->where('id='.$id)->delete();
		if($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getRoomStatus($id){
		$map['id'] = $id;
        $result = $this->where($map)->find();
        return $result['status'];
	}
	
	public function getRoomToken($id){
		$map['id'] = $id;
        $result = $this->where($map)->find();
        return $result['token'];
	}

	public function setRoomStatus($id,$status){
	   $map['status'] = $status;
	   $result = $this->where("id=".$id)->save($map);
	   return $result;	
	}

    private function replace($data){
    	$result = $data;
    	$posterSmallTypeDao = D('PosterSmallType');
    	$posterTypeDao = D('PosterType');
    	$posterST = $posterSmallTypeDao->getPosterSmallTypeByIdArray();
    	$posterT = $posterTypeDao->getPosterTypeByIdArray();
        $posterType = D('PosterType');
    	foreach($result as &$value){
           $value['type'] = $posterST[$value['type']];
           $value['content'] = getBlogShort($value['content'],20);
           $value['posterType'] = $posterT[$value['pid']];
           $value['cover'] && $value['cover'] = './data/uploads/'.$value['cover'];
    	}
    	return $result;
    }
}