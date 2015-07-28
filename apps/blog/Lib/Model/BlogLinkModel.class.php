<?php
/**
+------------------------------------------------------------------------------
 * 迷你博客Model层。操作相关的数据业务逻辑Model
+------------------------------------------------------------------------------
 * @category	BLOG
 * @package		Lib/Model
 * @author		ssq
+------------------------------------------------------------------------------
 * 创建时间：	2014-1-3 下午01:19:33
+------------------------------------------------------------------------------
 */
class BlogLinkModel extends Model {
    var $tableName = 'blog_link';
		
    public function addBlodLink($param){
    	$retult = $this
    			->add($param);
    	return $retult;
    }
    
    public function delBlog($param){
    	$retult = $this
    			->where($param)
    			->delete();
    	return $retult;
    }
}
