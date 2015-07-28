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
class SquareCategoryModel extends Model {
	protected $tableName = 'square_category';
	/**
	 +----------------------------------------------------------
	 * 在增加栏目时增加‘其他’分类
	 +----------------------------------------------------------
	 * @return	int 增加的栏目id
	 * @author	美美2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 上午02:32:20
	 +----------------------------------------------------------
	 */
	function addelse()
	{
		//增加一个栏目
		$fa['category_name'] = '其他';
		$fa['p_id'] = $data['p_id'];
		$fa['path'] = $path['path'].'-';
		$addfa=$this->add($fa);
		//补全路径信息
		$fa['path'] = $path['path'].'-'.$addfa;
		$savefa = $this->where('id='.$addfa)->save($fa);
		return $addfa;
	}
	/**
	 +----------------------------------------------------------
	 * 树形结构	|-形式
	 +----------------------------------------------------------
	 * @param	int id 要获取的根id
	 * @return	Array 重塑的树形结构
	 * @author	美美2013-3-4
	 +----------------------------------------------------------
	 * 创建时间：	2013-3-4 上午02:32:51
	 +----------------------------------------------------------
	 */
	function getTree($id)  
	{  
		//获取所有要组合的
		$deptlist = M('square_category')->where("path like '0-".$id."%'")->order('display_order')->findAll();
		$list = array();
		include_once SITE_PATH.'/addons/libs/Tree.class.php';
	    $tree = new Tree();
		$nums = count($deptlist);
		foreach($deptlist as $key => $deptobj) {
			$tree->setNode($deptobj['id'],$deptobj['p_id'], $deptobj);
		}
		$childs = $tree->getChilds(0);
		$uplevel="";
		foreach ($childs as $key => $vid) {
			$one = $tree->getValue($vid);
			$one['level'] = $tree->getLayer($vid) ;
			for($i=1;$i<$one['level'];$i++)
			{
				$one['style1']=$one['style1']."| - ";
			}
			$list[] = $one;
		}
		return $list;
    }  
    /**
     +----------------------------------------------------------
     * 栏目检索的树形结构
     +----------------------------------------------------------
     * @param	int id 要获取的根id
     * @return	Array 重塑的树形结构
     * @author	美美2013-3-4
     +----------------------------------------------------------
     * 创建时间：	2013-3-4 上午02:34:16
     +----------------------------------------------------------
     */
   function getTrees($id)  
	{  
		$deptlist = M('square_category')->where("path like '0-".$id."-%'")->order('display_order asc')->findAll();
		$list = array();
		include_once SITE_PATH.'/addons/libs/Tree.class.php';
	    $tree = new Tree();
		$nums = count($deptlist);
		foreach($deptlist as $key => $deptobj) {
			$tree->setNode($deptobj['id'],$deptobj['p_id'], $deptobj);
		}
		$childs = $tree->getChilds($id);

		$uplevel="";
		foreach ($childs as $key => $vid) {
			$one = $tree->getValue($vid);
			$one['level'] = $tree->getLayer($vid) ;

			if($one['level']==$uplevel)
			{
				if($key==$nums-1)
				{
				    $t=$one['level'];
				    while($t>=0)
					{
						if($t==0)
						{
						    $one['style2'].="</li>";
						}
						if($t>0)
						{
							$one['style2'].="</li></ul>";
						}
						$t=$t-1;
					}
				}

				$one['style1']="</li><li>";
			}

			if($uplevel=="")
			{
				$one['style1']="<li>";
			}
			if($one['level']==$uplevel&&$one['level']==0&&$uplevel==""&&$key!=0)
			{
				$one['style1']="</li><li>";
			}

 			if($one['level']>$uplevel)
			{
				if($key==$nums-1)
				{
				    $t=$one['level'];
				    while($t>=0)
					{
						if($t==0)
						{
				            $one['style2'].="</li>";
						}
						if($t>0)
						{
							$one['style2'].="</li></ul>";
						}
						$t=$t-1;
			 	     }
				}
				$one['style1']="<ul><li>";
			}

			if($one['level']<$uplevel)
			{

				$u=$uplevel;
				$t=$one['level'];
				while(($u-$t)>=0)
			    {
					if(($u-$t)==0)
					{
				    	$one['style1'].="</li><li>";
					}
					if(($u-$t)>0)
					{
						$one['style1'].="</li></ul>";
					}
					$u=$u-1;
				}

				if($key==$nums-1)
				{
				    while($t>=0)
					{
						if($t==0)
						{
				            $one['style2'].="</li>";
						}
						if($t>0)
						{
							$one['style2'].="</li></ul>";
						}
						$t=$t-1;
					}
				}
			 }

			$uplevel=$one['level'];
			$list[] = $one;
		}
		return $list;
    }  
}
?>