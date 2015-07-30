<?php
class UndercoverWordModel extends Model{
	
	public function checkWord($w1,$w2){
	   $map['word1'] = $w1;
	   $map['word2'] = $w2;

	   $map2['word1'] = $w2;
	   $map2['word2'] = $w1;
	   
	   $r = $this->where($map)->find();
       if($r){
          return true;
       }else{
           $r2 = $this->where($map2)->find();
           if($r2){
           	  return true;
           }else{
           	  return false;
           }
        }	   	
	}

	public function addWord($data){
		//检查数据是否为空
		if(empty($data['word1'])){
			return -1;
		}
		if(empty($data['word2'])){
			return -2;
		}
		
		if($this->checkWord($data['word1'],$data['word2'])){
		   return -3;
		}
		
		//添加数据
        $rs = $this->add($data);
        return $rs;
	}
	
	public function getWord($id=null){

       if(isset($id)){
          if(is_array($id)){
                $map['id'] = array('in',$id);
            }else{
                $map['id'] = $id;
            }
          $rs = $this->where($map)->findAll();
          if(isset($id) && !is_array($id)){
             $rs = $rs[0];
          }
          return $rs;
       }else{
          $rs = $this->where($map)->order("id DESC")->findPage(20);
          return $rs;
       }
	}
    
	public function countWord(){
	   $n = $this->count();	
	   return $n;
	}
	
}