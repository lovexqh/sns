<?php
/**
+------------------------------------------------------------------------------
 * 迷你相册Model层。操作相关的数据业务逻辑Model
+------------------------------------------------------------------------------
 * @category	photo
 * @package		Lib/Model
 * @author		ssq
+------------------------------------------------------------------------------
 * 创建时间：	2014-1-3 下午01:19:33
+------------------------------------------------------------------------------
 */
class PhotoLinkModel extends Model {
    var $tableName = 'photo_link';
		
    public function addPhotoLink($param){
    	$retult = $this
    			->add($param);
    	return $retult;
    }
    
    public function delPhoto($param){
    	$retult = $this
    			->where($param)
    			->delete();
    	return $retult;
    }
}
