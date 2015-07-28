<?php
class ToolModel extends BaseModel{
  var $tableName	=	'tool';
  
  /**
	 +----------------------------------------------------------
	 * 删除资源
	 +----------------------------------------------------------
	 * @author	小朱
	 +----------------------------------------------------------
	 * 创建时间：	2013-7-31 上午03:40:10
	 +----------------------------------------------------------
	 */
  public function delResource($ids){
	$saveaddress = D('ToolFile')->field('saveaddress')->where($ids)->group('saveaddress')->findAll();
	$delFilecode=array();
	foreach ($saveaddress as $val){
  		$filecode = M('ToolFile')->field('filecode')->where($ids)->findAll();
		foreach ($filecode as $k=>$v){
			$filecode[$k]=$v['filecode'];;
		}
		$filecode=implode("-",$filecode);
		
		// 1. 初始化
		$ch = curl_init();
		// 2. 设置选项，包括URL
		curl_setopt($ch, CURLOPT_URL, $val['saveaddress']."manage.aspx?action=del&filecode=".$filecode);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //这行是设定curl是否跟随header发送的location
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "");
		// 3. 执行并获取HTML文档内容
		$output = curl_exec($ch);
		// 4. 释放curl句柄
		curl_close($ch);
		$obj=json_decode($output);
		foreach($obj as $v){
			if($v->result==true || $v->result==notexist)
				$delFilecode=$v->filecode;
				
		}
	}
	//已经在服务器上删除的资源
	$map['filecode'] = array( 'in',explode(',',$delFilecode));
	$option=D('ToolFile')->field('id')->where($map)->findAll();
	foreach($option as $obj){
		$ids=$obj['id'];
	}
	$where['id'] = $ids['id'];
	if($this->where($where)->delete()){
		D('ToolFile')->where($where)->delete();
		D('ToolDown')->where($where)->delete();
		D('ToolScore')->where($where)->delete();
		return true;
	}
	return false;
  }
  /**
   * 系统推荐
   * @author liman 2013-8-20
   * @return $result
   */
  
  public function gethostlist($uid){
  	$result=M('')
  		->table('ts_tool t')
  		->join('ts_square_category sc on t.section=sc.id')
  		->field('t.*,sc.category_name')
  		->order('t.downCount desc')
  		->where('t.uid !='.$uid)
  		->findPage(10);
  	return $result;
  }
  
  
  
  
}
?>