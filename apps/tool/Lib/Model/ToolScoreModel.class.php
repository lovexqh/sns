<?php
class ToolScoreModel extends Model{
  var $tableName	=	'tool_score';


  public function getScore($id){
	  
	 $score=D('ToolScore')->where("id=$id")->avg('score');
	 $score=intval($score);
	 return $score; 
  }
}
?>