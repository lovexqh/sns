<?php
class PosterModel extends Model{
	private $api;


	public function _initialize(){
	     	//$this->api = new TS_API();
	}

	public function getArea($provinceid=null, $cityid=null){
		$area = M("area");
		$result = $area->where('area_id='.$provinceid.' or area_id='.$cityid)->select();
		return $result;
	}
	
	public function getPosterList($pid=null,$type=null,$uid = null,$title = null,$key=null){
		isset($pid) && $map['pid'] = $pid;
		isset($type) && $map['type'] = $type;
        isset($title) && $map['title'] = array( 'like','%'.$title.'%' );
		if(is_array($uid)&&!empty($uid)){
			$map['uid'] = array('in',$uid);
		}elseif(intval($uid)){
			$map['uid'] = $uid;
		}
		
		if(!empty($key)){
			$map['title'] = array('like','%'. $key .'%');
		}
		
	    $result = $this->where($map)->order('cTime DESC')->field('id,pid,type,uid,content,title,deadline,private,cover,cTime')->findPage(12);
	    $result['data'] = $this->replace($result['data']);
	    return $result;
	}
	public function getPoster($id,$mid){
		$map['id'] = $id;
        $result = $this->where($map)->find();
        $posterSmallTypeDao = D('PosterSmallType');
        $posterTypeDao = D('PosterType');
        if(!$result) return false;
        //if($mid !=$result['uid'] && $result['private'] == 1 && 'unfollow' == getFollowState($result['uid'],$mid)) return false;
        $result['posterType'] = $posterTypeDao->getTypeName($result['pid']);
        $result['posterSmallType'] = $posterSmallTypeDao->getTypeName($result['type']);
        isset($result['cover']) && $result['cover'] = './data/uploads/'.$result['cover'];
        $result['address'] = getAreaInfo($result['address_province'].','.$result['address_city']);
        return $result;
	}

	public function deletePoster($id,$mid){
		$poster = $this->where('id='.$id)->find();
		if(!$poster) return -2;
		if($poster['uid'] != $mid) return -1;

		$rs = $this->where('id='.$id)->delete();
		if($rs){
			return 1;
		}else{
			return 0;
		}
	}
	
	public function getFavorityPoster($map){
		$result = $this->where($map)->order('cTime DESC')->field('id,pid,type,uid,content,title,deadline,private,cover,cTime')->findPage(12);
		$result['data'] = $this->replace($result['data']);
		return $result;
	}

    private function replace($data){
    	$result = $data;
    	$posterSmallTypeDao = D('PosterSmallType','poster');
    	$posterTypeDao = D('PosterType','poster');
    	$posterST = $posterSmallTypeDao->getPosterSmallTypeByIdArray();
    	$posterT = $posterTypeDao->getPosterTypeByIdArray();
        $posterType = D('PosterType','poster');
    	foreach($result as &$value){
           $value['type'] = $posterST[$value['type']];
    //       $value['content'] = getShort($value['content'],20);
           $value['posterType'] = $posterT[$value['pid']];
           $value['cover'] && $value['cover'] = './data/uploads/'.$value['cover'];
    	}
    	return $result;
    }
    
    public function getPosterByParam($map, $page, $num){
    	$num = !empty($num) ? $num : 20;
    	$start = ($page - 1) * intval($num);
    	$posterList = $this->where($map)->order('cTime DESC')->limit($start.','.$num)->select();
    	return $posterList;
    }
    
    public function getFavPosterInfo($uid){
    	$result = M('favorite')->where("favuid=".$uid." AND appname='poster'")->field('id,favuid,fid,addtime')->select();
    	return $result;
    }
    
    public function getPosterById($id){
    	$posterInfo = $this->where('id='.$id)->find();
    	return $posterInfo;
    }
    
    /**
     * 查看某uid的招贴
     * @author:lihao
     */
    public function getMyPosterList($uid, $page, $useful = null, $pagesize = null){
    	$PosterSmallType = D('PosterSmallType','poster');
    	$PosterType = D('PosterType','poster');
  		//当前时间戳
  		$time = time();
  		if(empty($pagesize)){
  			$pagesize = 20;
  		}
  		if(empty($useful)){//0不过滤已过期的，1过滤已过期只显示未过期的，2只显示已过期的
  			$useful = 0;
  		}
  		if($useful == 0){
  			$cache = $this->where(' uid = '.$uid.' ')->order(' cTime DESC ')
  						->field(' id,pid,type,uid,content,title,deadline,private,cover,cTime ')
  						->limit($page.','.$pagesize)->select();
  		}else if ($useful == 1){
  			$cache = $this->where(' uid = '.$uid.' and deadline >= '.$time.' ')->order(' cTime DESC ')
  						->field(' id,pid,type,uid,content,title,deadline,private,cover,cTime ')
  						->limit($page.','.$pagesize)->select();  			
  		}else if ($useful == 2){
  			$cache = $this->where(' uid = '.$uid.' and deadline < '.$time.' ')->order(' cTime DESC ')
  						->field(' id,pid,type,uid,content,title,deadline,private,cover,cTime ')
  						->limit($page.','.$pagesize)->select(); 
  		}
    	foreach ($cache as &$value){
    		$value['stname'] = $PosterSmallType->getTypeName($value['type']);
     		$value['pname'] = $PosterType->getTypeName($value['pid']);
     		$value['uname'] = getUserName($value['uid']);
     		$value['uhead'] = getUserFace($value['uid']);
			if(!empty($value['cover'])){
				$value['cover'] = './data/uploads/' . $value['cover'];
			}
			$value['content'] = htmlspecialchars($value['content']);
			$topic['cTime'] = date("Y-m-d",$topic['cTime']);
    	}
    	return $cache;
    }
    
    /**
     * 查看某uid收藏的招贴
     * @author:lihao
     */
    public function getMyFavPoster($uid, $page, $useful = null, $pagesize = null){
    	$FavPoster = model('favorite');
    	//当前时间戳
    	$time = time();
    	if(empty($pagesize)){
    		$pagesize = 20;
    	}
    	if(empty($useful)){//0不过滤已过期的，1过滤已过期只显示未过期的，2只显示已过期的
    		$useful = 0;
    	}
    	if($useful == 0){
    		$cache = $FavPoster->field(' distinct poster.*, poster_small_type.name as stname, poster_type.name as pname ')
    		->table(C('DB_PREFIX').'favorite as favorite inner join '.C('DB_PREFIX').'poster as poster 
    						on favorite.favuid = poster.uid inner join '.C('DB_PREFIX').'poster_small_type as poster_small_type
					  		on poster.type = poster_small_type.label inner join '.C('DB_PREFIX').'poster_type as poster_type
					  		on poster.pid = poster_type.id ')
    		->where('poster.uid = '.$uid.' ')
    		->limit($page.','.$pagesize)->select();
    	}else if($useful == 1){
    		$cache = $FavPoster->field(' distinct poster.*, poster_small_type.name as stname, poster_type.name as pname ')
    		->table(C('DB_PREFIX').'favorite as favorite inner join '.C('DB_PREFIX').'poster as poster
    						on favorite.favuid = poster.uid inner join '.C('DB_PREFIX').'poster_small_type as poster_small_type
					  		on poster.type = poster_small_type.label inner join '.C('DB_PREFIX').'poster_type as poster_type
					  		on poster.pid = poster_type.id ')
    		->where('poster.uid = '.$uid.' and deadline < '.$time.' ')
    		->limit($page.','.$pagesize)->select();
    	}else if($useful == 2){
    		$cache = $FavPoster->field(' distinct poster.*, poster_small_type.name as stname, poster_type.name as pname ')
    		->table(C('DB_PREFIX').'favorite as favorite inner join '.C('DB_PREFIX').'poster as poster
    						on favorite.favuid = poster.uid inner join '.C('DB_PREFIX').'poster_small_type as poster_small_type
					  		on poster.type = poster_small_type.label inner join '.C('DB_PREFIX').'poster_type as poster_type
					  		on poster.pid = poster_type.id ')
    		->where('poster.uid = '.$uid.' and deadline < '.$time.' ')
    		->limit($page.','.$pagesize)->select();
    	}
    	foreach ($cache as &$value){
    		$value['uname'] = getUserName($value['uid']);
    		$value['uhead'] = getUserFace($value['uid']);
    		$value['cover'] = './data/uploads/' . $value['cover'];
    		$value['content'] = htmlspecialchars($value['content']);
    		$topic['cTime'] = date("Y-m-d",$topic['cTime']);
    	}
    }
    
    /**
     * 查看所有招贴
     * @author：lihao
     */
    public function getAllPoster($uid, $page, $useful = null, $pagesize = null){
    	//当前时间戳
    	$PosterSmallType = D('PosterSmallType','poster');
    	$PosterType = D('PosterType','poster');
    	$time = time();
    	if(empty($pagesize)){
    		$pagesize = 20;
    	}
    	if(empty($useful)){//0不过滤已过期的，1过滤已过期只显示未过期的，2只显示已过期的
    		$useful = 0;
    	}
    	$cache = array();
     	if($useful == 0){
     		$cache = $this->order(' cTime DESC ')
     					  ->field(' id,pid,type,uid,content,title,deadline,private,cover ')
     		              ->limit($page.','.$pagesize)->select();
     	}else if ($useful == 1){
    	    $cache = $this->where(' deadline >= '.$time.' ')->order(' cTime DESC ')
    					  ->field(' id,pid,type,uid,content,title,deadline,private,cover,cTime ')
    		              ->limit($page.','.$pagesize)->select();
    	}else if ($useful == 2){
    	    $cache = $this->where(' deadline < '.$time.' ')->order(' cTime DESC ')
    					  ->field(' id,pid,type,uid,content,title,deadline,private,cover,cTime ')
    		              ->limit($page.','.$pagesize)->select();
    	}
    	foreach ($cache as &$value){
    		$value['stname'] = $PosterSmallType->getTypeName($value['type']);
     		$value['pname'] = $PosterType->getTypeName($value['pid']);
     		$value['uname'] = getUserName($value['uid']);
     		$value['uhead'] = getUserFace($value['uid']);
			if(!empty($value['cover'])){
				$value['cover'] = './data/uploads/' . $value['cover'];
			}
			$value['content'] = htmlspecialchars($value['content']);
			$topic['cTime'] = date("Y-m-d",$topic['cTime']);
    	}
    	return $cache;
    }
    
    public function getReplyByPosterid($id, $page, $num){
    	$num = !empty($num) ? $num : 20;
    	$start = ($page - 1) * intval($num);
    	$map['type'] = 'poster';
    	$map['appid'] = $id;
    	$replyList = M('comment')->where($map)->order('cTime desc')->limit($start.','.$num)->select();
    	return $replyList;
    }
    
    public function getReplyById($id){
    	$reply = M('comment')->where('id='.$id)->find();
    	return $reply;
    }
    
    public function getReplyCount($id){
    	$map['type'] = 'poster';
    	$map['appid'] = $id;
    	$count = M('comment')->where($map)->count();
    	return $count;
    }
    
    public function addReply($map){
    	$rs = M('comment')->add($map);
    	return $rs;
    }
    
    public function addPoster($map){
    	$rs = $this->add($map);
    	return $rs;
    }
    
    public function delPoster($id){
    	$rs = $this->where('id='.$id)->delete();
    	return $rs;
    }
}