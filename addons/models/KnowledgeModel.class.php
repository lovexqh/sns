<?php
/**
 * 收藏模型
 * 
 * @author Ricker <rickeryu@gridinfo.com.cn>
 */
class KnowledgeModel extends Model {
	protected $tableName = 'category_knowledge';
	private $config;
	
	/**
	 +----------------------------------------------------------
	 * 树状节点
	 +----------------------------------------------------------
	 * @param	$type 为当前节点的属性
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-10 上午11:07:09
	 +----------------------------------------------------------
	 */
	public function getTreeNode($type,$section,$option) {
		$this->config = $this->getConfig();
		//$type 为当前单击节点的标识属性，计算出当前的排位顺序，然后+1，计算出该单击查询的下一等级
		$order=array_search($type,$this->config['order'])+1;	//计算出单机节点位于配置中的位置
		
		//获取当前传递值有没有Grade，若没有则表名该单击节点为小学、初中、高中根基点
		//if(empty($option['Grade'])){
			$map['Grade']=$this->getSectionMap($section);	//从配置文件中获取学段相应的年级
		//}
		
		if($type=='section' || $type=='Grade' || $type=='Publisher' || $type=='Subject' || $type=='Volume' || $type=='Cell'){
			if($order>1){
				for($i=$order-1;$i>=1;$i--){
					$map[$this->config['order'][$i]]=$option[$this->config['order'][$i]];
				}
				if($map['Grade'])
					$section=$this->getSection($map['Grade']);
			}
			$data=$this->getKnowledge($order,$map,$section);
		}else{
			$data=array(
				array('id'=>1, 'name'=>'小学','type'=>'section','isParent'=>true,'section'=>1),
				array('id'=>2, 'name'=>'初中','type'=>'section','isParent'=>true,'section'=>2),
				array('id'=>3, 'name'=>'高中','type'=>'section','isParent'=>true,'section'=>3),
			);
		}
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取子节点公用方法
	 +----------------------------------------------------------
	 * @param	$order 或者当前查询的是第几节点
	 * @param	$map 查询条件
	 * @param	$section 学段
	 * @return	array $data 查询结果
	 * @author	小朱 2013-5-10
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-10 下午01:26:09
	 +----------------------------------------------------------
	 */
	private function getKnowledge($order=0,$map,$section){
		$sort=$this->config['order'][$order];
		if($order>1){
			$str='';
			for($i=$order-1;$i>=1;$i--){
				$str.=",'".$map[$this->config['order'][$i]]."' as ".$this->config['order'][$i];
			}
		}
		if($map['Grade'] && $map['Subject'] && $map['Publisher'] && $map['Volume'] && $map['Cell'] && count($map['Grade'])==1){
			$data=M('category_knowledge')->field("Course as name,".$sort." as ".$sort.",CourseID as id,'Course' as type,'false' as isParent ".$str.",'true' as Course,'".$section."' as section")->where($map)->findall();
		}else{
			$data=M('category_knowledge')->field($sort." as id,".$sort." as ".$sort.",(SELECT `DataName` FROM `ts_category_dictionary` WHERE `DataCode` = `".$sort."` order by DataOrder ASC) as name,'".$sort."' as type,'true' as isParent ".$str.",'".$section."' as section")->where($map)->group($sort)->findall();
		}
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 根据年纪给session赋值
	 +----------------------------------------------------------
	 * @param	$grade 年级
	 * @author	小朱 2013-5-11
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-11 上午10:00:36
	 +----------------------------------------------------------
	 */
	public function getSection($garde){
		$this->config = $this->getConfig();
		if(in_array($garde,$this->config['section']['primary']))
			$section=1;
		else if(in_array($garde,$this->config['section']['junior']))
			$section=2;
		else if(in_array($garde,$this->config['section']['high']))
			$section=3;
		return $section;
	}
	
	
	/**
	 +----------------------------------------------------------
	 * 重配置文件中获取相应的节点对应的年级
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-11
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-11 上午10:00:36
	 +----------------------------------------------------------
	 */
	private  function getSectionMap($id){
		
		if($id==1){
			$res  = array('in',$this->config['section']['primary']);
		}else if($id==2){
			$res  = array('in',$this->config['section']['junior']);
		}else if($id==3){
			$res  = array('in',$this->config['section']['high']);
		}
		return $res;
	}
	
	/**
	 +----------------------------------------------------------
	 * 配置文件
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-11
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-11 上午10:00:36
	 +----------------------------------------------------------
	 */
	public function getConfig($key=NULL){
		$config['section']['primary'] =array(0=>'Grade1', 1=>'Grade2',2=>'Grade3', 3=>'Grade4',4=>'Grade5'); 
		$config['section']['junior'] =array(0=>'Grade6', 1=>'Grade7',2=>'Grade8', 3=>'Grade9'); 
		$config['section']['high'] =array(0=>'High1', 1=>'High2',2=>'High3');
		$config['order']=array(0=>'section',2=>'Subject', 1=>'Grade',3=>'Publisher',4=>'Volume',5=>'Cell',6=>'CourseID');
		$config['limitpage']=20;
		return $config;
	}
	
	/**
	 +----------------------------------------------------------
	 * 异步加载树状结构
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-11
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-11 上午10:00:36
	 +----------------------------------------------------------
	 */
	public function getTreeArray($show){
		$this->config = $this->getConfig();
		//默认显示
		$data=array(
			array('id'=>'node0', 'pId'=>0,'name'=>'共建共享','type'=>'section','isParent'=>'true','section'=>1,'open'=>'true'),
			array('id'=>'node1', 'pId'=>0,'name'=>'初中','type'=>'section','isParent'=>'true','section'=>2,'open'=>'true'),
			array('id'=>'node2', 'pId'=>0,'name'=>'高中','type'=>'section','isParent'=>'true','section'=>3,'open'=>'true')
		);
		if($show){
			for($i=0;$i<6;$i++){
				if($i==0){
					if(in_array($show['grade'],$this->config['section']['primary'])){
						$map['Grade']=$this->getSectionMap(1);
						$section='node0';
					}else if(in_array($show['grade'],$this->config['section']['junior'])){
						$map['Grade']=$this->getSectionMap(2);
						$section='node1';
					}else if(in_array($show['grade'],$this->config['section']['high'])){
						$map['Grade']=$this->getSectionMap(3);
						$section='node2';
					}	
				}else{
					for($j=$i;$j>=1;$j--){
						$charname=strtolower($this->config['order'][$j]);
						$map[$this->config['order'][$j]]=$show[$charname];
					}
				}
				$char=strtolower($this->config['order'][($i+1)]);
				$key=$show[$char];
				$return=$this->getKnowLedgeNode($i,$map,$section);
				foreach($return as $vo){
					$vo['id']='node'.count($data);
					if(($vo['type']=='Grade' && $vo['Grade']==$key) || ($vo['type']=='Subject' && $vo['Subject']==$key) || ($vo['type']=='Publisher' && $vo['Publisher']==$key) || ($vo['type']=='Volume' && $vo['Volume']==$key) || ($vo['type']=='Cell' && $vo['Cell']==$key)){
						$vo['open']='true';
						$section=$vo['id'];
					}
					$data[]=$vo;
				}
			}
		}
		return $data;
	}
	
	/**
	 +----------------------------------------------------------
	 * 查找知识点相应字段
	 +----------------------------------------------------------
	 * @param	<按照参数定义顺序(没有参数的方法就不用该选项)>
	 * @return	return_type <返回类型(void的方法就不用该选项)>
	 * @author	小朱 2013-5-11
	 +----------------------------------------------------------
	 * 创建时间：	2013-5-11 上午10:00:36
	 +----------------------------------------------------------
	 */
	public function getKnowLedgeNode($order,$map,$section){
		if($order>0){
			$str='';
			for($i=$order;$i>=1;$i--){
				$str.=",'".$map[$this->config['order'][$i]]."' as ".$this->config['order'][$i];
			}
		}
		if($order==5){
			$sort=$this->config['order'][($order+1)];
			$data=M('category_knowledge')->field("CourseID as id,Course as name,'".$section."' as pId,".$sort." as ".$sort.",'Course' as type,'false' as isParent ".$str.",'true' as Course, 'false' as open")->where($map)->findall();
			
		}else{
			$sort=$this->config['order'][($order+1)];
			$data=M('category_knowledge')
			->field("'".$section."' as pId,".$sort." as ".$sort.",(SELECT `DataName` FROM `ts_category_dictionary` WHERE `DataCode` = `".$sort."` order by DataOrder ASC) as name,'".$sort."' as type,'true' as isParent ".$str.",'false' as open")
			->where($map)->group($sort)->findall();
		}
		return $data;
	}

    /**
	 +----------------------------------------------------------
	 * 默认展开n级树状节点
	 +----------------------------------------------------------
	 * @param	$a int 要展开的级数
	 * @param	$b int 计数变量
	 * @param	$pId string 父ID
	 * @param	$type string 节点类型
	 * @param	$section string 学段ID
	 * @param	$option string 其他条件
	 * @return	return_type array
	 * @author	杨志明 2013-7-2
	 +----------------------------------------------------------
	 * 创建时间：	2013-7-2
	 +----------------------------------------------------------
	 */
	function getTree($a, $b = 0, $nodeNum = 0, &$c = 0, $pId = '', $type = '', $section = '', $option = array()){
		if($b>$a || $b>5 || ($nodeNum!=0 && $c>=$nodeNum)) return array();
	
		$data = array();
	
		if($b == 0){
			$data2 = model('Knowledge')->getTreeArray();
			$data = array_merge_recursive($data,$data2);
			$c += count($data2);
			
			$type = 'section';
			
			foreach($data2 as $k=>$v){
				$option['section'] = $k+1;
				$data3 = $this->getTree($a, $b+1, $nodeNum, $c, $v['id'], $type, $option['section'], $option);
		
				$data = array_merge_recursive($data,$data3);
			}
		} else {
			$data2 = model('Knowledge')->getTreeNode($type,$section,$option);
			foreach($data2 as $k=>$v) {
				$data2[$k]['id2'] = $data2[$k]['id'];
				$data2[$k]['id'] = $pId.$data2[$k]['id'];
				$data2[$k]['pId'] = $pId;
				$data2[$k]['open'] = 'true';
			}
			$data = array_merge_recursive($data,$data2);
			$c += count($data2);
			
			switch ($b){
				case 1:
					$type = 'Grade';
					break;
				case 2:
					$type = 'Subject';
					break;
				case 3:
					$type = 'Publisher';
					break;
				case 4:
					$type = 'Volume';
					break;
				case 5:
					$type = 'Cell';
					break;
			}
			
			foreach($data2 as $k=>$v){
				switch ($b){
					case 1:
						$option['Grade'] = $v['id2'];
						break;
					case 2:
						$option['Subject'] = $v['id2'];
						break;
					case 3:
						$option['Publisher'] = $v['id2'];
						break;
					case 4:
						$option['Volume'] = $v['id2'];
						break;
					case 5:
						$option['Cell'] = $v['id2'];
						break;
				}
				$data3 = $this->getTree($a, $b+1, $nodeNum, $c, $v['id'], $type, $section, $option);
			
				$data = array_merge_recursive($data,$data3);
			}
		}
	
		return $data;
	}
}