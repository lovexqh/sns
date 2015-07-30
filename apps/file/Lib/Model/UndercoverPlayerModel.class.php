<?php
class UndercoverPlayerModel extends Model{
	
	public function getPlayers($id,$status){
		$map['roomid'] = $id;
		if(isset($status)) $map['status'] = array('neq',$status);
	    $res = $this->where($map)->order("id DESC")->findAll();
	    $i =0;
	    foreach($res as $r){
	       if(empty($r['word'])){
	       	  $res[$i]['word'] = '-';
	       }
	       $res[$i]['playername'] = getUserName($r['playerid']);
	       $res[$i]['os'] = isOnline($r['playerid']);
	       $i++;
	    }
	    return $res;
	}	
	
	public function getMyStatus($id,$mid){
		$map['roomid'] = $id;
		$map['playerid'] = $mid;
	    $res = $this->where($map)->find();
	    return $res;
	}		
	
	
	public function checkJoinRoom($id,$mid){
	   $map['roomid'] = $id;
	   $map['playerid'] = $mid;
	   $res = $this->where($map)->find();
	   if($res){
	   	  return true;
	   }else{
	   	  return false;
	   }
		
	}

	public function countJoin($mid){
	   $map['playerid'] = $mid;
	   return $this->where($map)->count();
	}
	
	public function joinIt($id,$mid){
	   $map['roomid'] = $id;
       $map['playerid'] = $mid;
       $map['word'] = '';
       $map['status'] = 0;
       $r = $this->add($map);
       if($r){
       	  return true;
       }else{
       	  return false;
       }
       
	}
	
	public function quit($id,$mid){
       $map['roomid'] = $id;
       $map['playerid'] = $mid;
       return $this->where($map)->delete();
	}
	
	public function startGame($id,$wtype,$ptype,$word1,$word2){
	   $word = D("UndercoverWord");
	   $room = D("UndercoverRoom");
	   $playWord = array();
	   if($wtype==1){
	      $wordNum = $word->countWord();
	   	  $wordRand = rand(1,$wordNum);	   
	   	  $getWord = $word->getWord($wordRand); 
	      $playWord[0] = $getWord['word1'];
	      $playWord[1] = $getWord['word2'];
	   }else{
	      $playWord[0] = $word1;
	      $playWord[1] = $word2;
	      $data['word1'] = $word1;
	      $data['word2'] = $word2;
	      $word->addWord($data);	   	
	   }
	   $wordRand2 = rand(0,1);
	   
	   $players = $this->getPlayers($id);
       $playerNum = count($players);
	   $i = 0;	   

	   $playerRand = rand(0,$playerNum-1);
	   
	   if($ptype==2){
	      $playerRand2 = rand(0,$playerNum-1);
	   }
	   
	   foreach($players as $player){
	   	  $players[$i]['status'] = 1;
	   	  if($wordRand2){
             $players[$i]['word'] = $playWord[0];
	   	  }else{
	   	  	 $players[$i]['word'] = $playWord[1];	
	   	  }
	   	  $i++;
	   	}
	   	if($wordRand2){
	   	   $players[$playerRand]['word'] = $playWord[1];
	   	}else{
	   	   $players[$playerRand]['word'] = $playWord[0];	
	    }
	    
	    if($ptype==2){
	       if($playerRand2!=$playerRand){
	          $players[$playerRand2]['word'] = "-"; 	
	       }	
	    }

       foreach($players as $p){
       	  $con['roomid'] = $id;
       	  $con['playerid'] = $p['playerid']; 
       	  $map['status'] = $p['status'];
       	  $map['word'] = $p['word'];
       	  $rs = $this->where($con)->save($map);
       }
       
       return $room->setRoomStatus($id,1);
		
	}
	
	public function overGame($id){
		$room = D("UndercoverRoom");
		$map['status'] = 0;
		$map['word'] = '';
		$r = $this->where("roomid=".$id)->save($map);
		if($r){
		   return $room->setRoomStatus($id,0);	
		}else{
		   return false;
		}
	}
	
    public function countPlayer($id){
       $map['roomid'] = $id;
       return $this->where($map)->count(); 	
    }

}