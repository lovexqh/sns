<?php
/**
 +------------------------------------------------------------------------------
 * 工具Model
 +------------------------------------------------------------------------------
 * @category	square/Tool
 * @package		Lib/Model
 * @author		liman
 * @version		1.0
 +------------------------------------------------------------------------------
 * 创建时间：	2013-8-15
 +------------------------------------------------------------------------------
 */
class ToolModel extends Model{
  var $tableName	=	'tool';

  
  	/**
     * changeCount
     * �޸��ղصĴ���
     * @param mixed $blogid
     * @access public
     * @return void
     */
    public function collectCount( $id ) {
        $sql = "UPDATE {$this->tablePrefix}tool SET collectCount=collectCount+1 WHERE id='$id' LIMIT 1 ";
        $result = $this->execute($sql);
        if ( $result ) {
            return true;

        }
        return false;
    }
	/**
     * changeCount
     * tool表中下载量+1
     * @param mixed $blogid
     * @access public
     * @return void
     */
    public function downCount( $id ) {
        $sql = "UPDATE {$this->tablePrefix}tool SET downCount=downCount+1 WHERE id='$id' LIMIT 1 ";
        $result = $this->execute($sql);
        if ( $result ) {
            return true;

        }
        return false;
    }
    /**
     * 首页统计工具及下载数量
     * @author liman
     * @return unknown
     */
     public function allTool(){
    	$allResult=M('')
    	->table(C('DB_PREFIX')."tool t,".C('DB_PREFIX')."tool_file f")
    	->field('count(t.id) as alltool,sum(t.downCount) as alldown')
    	->where("t.id=f.id")
    	->find();
	
    	//echo M()->getLastSql();exit();
    	return $allResult;
    } 
    /**
     * 在square_category表里查找和tool表共有的工具类（p_id=4）下的所有大类
     * @return unknown
     */
    public function allType($limit=7){
      $typeResult=M()->table("".C('DB_PREFIX')."tool t,".C('DB_PREFIX')."square_category sc")
		->field('count(t.id) as count,t.*,sc.id as id')
		->where("t.class=sc.id")
		->group('t.class_name')
		->order('count(t.id) desc')
		->limit($limit)
		->findall();
     
    	return $typeResult;
    }
    
    
    /**
     * 首页官方/大众软件
     * @author liman 2013-8-15
     * @return string
     */
    public function publicofficial_Tool($map,$limit,$order){
    	$newResult=M('')
    			->table(C('DB_PREFIX').'tool t')
     			->join(C('DB_PREFIX').'square_category sc ON t.section=sc.id')
    			->join(C('DB_PREFIX').'tool_file f ON f.id=t.id')
    			->field('t.*,f.thumb,sc.category_name category')
    			->where($map)
				->limit($limit)
				->order('t.time DESC')
				->findall();
    	return $newResult;
    }
    /**
     * 首页热门软件
     * @author liman 2013-8-16
     * @return $hotResult
     */
    public function hotTool(){
    	$hotResult=M('')
    			->table(C('DB_PREFIX').'tool t')
    			->join(C('DB_PREFIX').'square_category category ON t.section=category.id')
    			->field('t.*,category.category_name')
    			->where('t.downCount !=0')
    			->limit(10)
    			->order('t.downCount desc,t.readCount desc')
    			->findall();
    	return $hotResult;
    			
    }
    /**
     * 获取分类下的信息 
     * @author liman 2013-8-16
     * @return unknown
     */
    public function getTree($limit=15,$wheres,$order='t.time DESC'){
    	$treeResult=M('tool as t')
    	->join('ts_square_category as category on category.id=t.section')
    	->join('ts_tool_file as tf on t.id=tf.id')
    	->field('t.*,tf.*,category.category_name as category')
    	->where($wheres)
    	->order($order)
    	->findPage($limit);
    	//echo M()->getLastSql();exit();
    	return $treeResult;
    }
    /**
     * 获取分类下的信息
     * @author liman 2013-8-16
     * @return unknown
     */
    public function getTree1($wheres,$order='downCount DESC'){
    	$treeResult=M('tool as t')
    	->join('ts_square_category as category on category.id=t.section')
    	->join('ts_tool_file as tf on t.id=tf.id')
    	->field('t.*,tf.*')
    	->where($wheres)
    	->order($order)
    	->limit(10)
    	->select();
    	//echo M()->getLastSql();exit();
    	return $treeResult;
    }
    
    /**
     * 获取大众 官方资源总数
     * @author liman 2013-8-16
     * @return unknown
     */
    public function p_o_all($wheres){
    	$treeResult=M('tool as t')
    	->join('ts_square_category as category on category.id=t.section')
    	->join('ts_tool_file as tf on t.id=tf.id')
    	->field('count(t.id) as count')
    	->where($wheres)
    	->limit(10)
    	->select();
    	//echo M()->getLastSql();exit();
    	return $treeResult;
    }
    /**
     +----------------------------------------------------------
     * 用户贡献排行榜
     +----------------------------------------------------------
     * @author	liman
     +----------------------------------------------------------
     * 创建时间：	2013-8-16
     +----------------------------------------------------------
     */
    public function Contribute(){
    	$result = D('Tool')->query("SELECT uid, count( 1 ) AS count FROM `".C('DB_PREFIX')."tool` GROUP BY uid ORDER BY count DESC limit 0,10");
    	return $result;
    }
    
    
}



?>